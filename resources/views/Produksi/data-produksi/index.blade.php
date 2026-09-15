@extends('layouts.produksi')

@section('title', 'Data Produksi')

@section('header-title', 'Data Produksi')
@section('header-badge', $stats['requested'] + $stats['diproses'] + $stats['menunggu_qc']. ' Aktif')
@section('header-subtitle', 'Kelola status produksi, pilih bahan baku dari stok gudang, dan pantau progres pesanan produksi.')

@section('content')
@php
    $transitions = [
        'requested' => ['diproses', 'dibatalkan'],
        'diproses' => ['menunggu_qc', 'dibatalkan'],
        'menunggu_qc' => ['dibatalkan'],
        'selesai' => [],
        'dibatalkan' => [],
    ];
    $statusLabels = [
        'requested' => 'Menunggu',
        'diproses' => 'Diproses',
        'menunggu_qc' => 'Menunggu QC',
        'selesai' => 'Selesai',
        'dibatalkan' => 'Dibatalkan',
    ];
    $statusBadge = [
        'requested' => 'bg-gold-accent/10 text-gold-accent border-gold-accent/30',
        'diproses' => 'bg-secondary-container/20 text-secondary border-secondary/20',
        'menunggu_qc' => 'bg-gold-accent/10 text-gold-accent border-gold-accent/30',
        'selesai' => 'bg-deep-onyx text-on-primary border-transparent',
        'dibatalkan' => 'bg-error/10 text-error border-error/20',
    ];
    $tabs = [
        'proses' => ['label' => 'Proses', 'count' => $stats['requested'] + $stats['diproses'] + $stats['menunggu_qc']],
        'riwayat' => ['label' => 'Riwayat', 'count' => $stats['selesai'] + $stats['dibatalkan']],
        'semua' => ['label' => 'Semua', 'count' => array_sum($stats)],
    ];
@endphp
<div data-skeleton class="space-y-section-gap">
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        @for ($i = 0; $i < 4; $i++)
            <div class="h-28 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="h-[420px] bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    {{-- Ringkasan --}}
    <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Menunggu</span>
            <span class="raliva-figure text-[26px] text-gold-accent">{{ $stats['requested'] }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">belum dimulai</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">schedule</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Diproses</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ $stats['diproses'] }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">(termasuk produksi ulang)</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">precision_manufacturing</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Menunggu QC</span>
            <span class="raliva-figure text-[26px] text-gold-accent">{{ $stats['menunggu_qc'] }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">perlu pemeriksaan</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">fact_check</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Selesai</span>
            <span class="raliva-figure text-[26px] text-secondary">{{ $stats['selesai'] }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">{{ $stats['dibatalkan'] }} dibatalkan</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">task_alt</span>
        </div>
    </section>

    {{-- Tabel Data Produksi --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
            <div class="inline-flex bg-surface-container-low border border-muted-border rounded-lg p-1 gap-1">
                @foreach ($tabs as $key => $tabInfo)
                    <a href="{{ route('produksi.data-produksi', array_merge(request()->except(['tab', 'page']), ['tab' => $key])) }}"
                       class="px-3 py-1.5 rounded-md text-xs font-medium transition-colors {{ $tab === $key ? 'bg-deep-onyx text-on-primary' : 'text-on-surface-variant hover:text-on-surface' }}">
                        {{ $tabInfo['label'] }} ({{ $tabInfo['count'] }})
                    </a>
                @endforeach
            </div>
            <form method="GET" action="{{ route('produksi.data-produksi') }}" class="flex items-center gap-3 w-full lg:w-auto">
                @if ($tab !== 'proses')
                    <input type="hidden" name="tab" value="{{ $tab }}" />
                @endif
                <div class="relative flex-1 min-w-[200px]">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                    <input type="text" name="cari" value="{{ $cari }}" placeholder="Cari kode / produk..." class="raliva-search !pl-10" />
                </div>
                <button type="submit" class="px-4 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors shrink-0">Cari</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="premium-table w-full min-w-[980px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Kode</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Produk / Target</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Bahan Terpakai</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Prioritas</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        @php
                            $diminta = $order->items->sum('jumlah_diminta');
                            $layak = $order->qualityChecks->sum('jumlah_lulus');
                            $sisa = max(0, $diminta - $layak);
                            $aktif = $order->status !== 'selesai' && $order->status !== 'dibatalkan';
                            $allowed = $transitions[$order->status] ?? [];
                            $alokasi = $order->materials->mapWithKeys(fn ($m) => [$m->production_material_id => (float) $m->jumlah_pakai])->toJson();
                        @endphp
                        <tr class="border-b border-muted-border last:border-0">
                            <td class="py-3.5 px-4 font-bold text-on-surface whitespace-nowrap">{{ $order->nomor_produksi }}</td>
                            <td class="py-3.5 px-4">
                                @foreach ($order->items as $item)
                                    <div class="flex items-center justify-between gap-3">
                                        <span class="text-on-surface font-bold">{{ $item->productVariant?->product?->nama_produk ?? '-' }}</span>
                                        <span class="text-xs text-on-surface-variant">{{ $item->jumlah_diminta }} unit</span>
                                    </div>
                                @endforeach
                                @if ($aktif)
                                    <div class="mt-2">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="font-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant">Layak terakumulasi</span>
                                            <span class="font-label-sm text-[11px] font-bold {{ $sisa === 0 ? 'text-secondary' : 'text-gold-accent' }}">{{ $layak }} / {{ $diminta }}</span>
                                        </div>
                                        <div class="h-1.5 bg-surface-container-high rounded-full overflow-hidden">
                                            <div class="progress-fill h-full rounded-full" data-progress-mode="task" data-progress="{{ $diminta ? round($layak / $diminta * 100) : 0 }}"></div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-xs text-on-surface-variant mt-1 inline-block">{{ $layak }} layak masuk gudang</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-on-surface-variant">
                                @if ($order->materials->isEmpty())
                                    <span class="text-on-surface-variant/60">Belum dipilih</span>
                                @else
                                    <ul class="space-y-0.5">
                                        @foreach ($order->materials as $mat)
                                            <li class="whitespace-nowrap">{{ $mat->material?->nama_bahan }} {{ rtrim(rtrim(number_format($mat->jumlah_pakai, 0, ',', '.'), '0'), ',') }} {{ $mat->material?->satuan }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">{{ ucfirst($order->prioritas) }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full {{ $statusBadge[$order->status] }} text-[10px] font-bold uppercase border">{{ $statusLabels[$order->status] }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                @if ($aktif)
                                    <button type="button"
                                        data-modal-open="modal-ubah-status"
                                        data-status-url="{{ route('produksi.data-produksi.status', $order) }}"
                                        data-statuses="{{ json_encode($allowed) }}"
                                        data-nomor="{{ $order->nomor_produksi }}"
                                        class="text-xs font-semibold text-gold-accent hover:underline mr-3">Ubah Status</button>
                                    <button type="button"
                                        data-modal-open="modal-kelola-bahan"
                                        data-bahan-url="{{ route('produksi.data-produksi.bahan', $order) }}"
                                        data-alokasi="{{ $alokasi }}"
                                        data-nomor="{{ $order->nomor_produksi }}"
                                        class="text-xs font-semibold text-on-surface hover:text-gold-accent hover:underline">Bahan</button>
                                @else
                                    <span class="text-xs text-on-surface-variant/50">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[28px] text-on-surface-variant">search_off</span>
                                    </div>
                                    <p class="text-on-surface-variant font-body-md text-sm">Tidak ada data produksi{{ $tab !== 'semua' ? ' pada tab '.$tab : '' }}.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($orders->hasPages())
            <div class="mt-6 flex justify-center">{{ $orders->links() }}</div>
        @endif
    </section>
</div>

{{-- Modal Ubah Status --}}
<div id="modal-ubah-status" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Ubah Status Produksi</h3>
                <p class="text-on-surface-variant font-body-md text-xs mt-1">Perbarui status produksi <span id="ub-nomor" class="font-bold text-gold-accent"></span>.</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="form-ubah-status" method="POST" class="p-6 space-y-5">
            @csrf
            <div>
                <label class="block raliva-label mb-2">Status Baru</label>
                <select id="ub-select" name="status" class="raliva-select" required></select>
            </div>
            <div>
                <label for="ub-catatan" class="block raliva-label mb-2">Catatan <span class="text-on-surface-variant/60">(opsional)</span></label>
                <textarea id="ub-catatan" name="catatan" rows="3" placeholder="cth. Bahan dipotong sesuai pola..." class="raliva-textarea"></textarea>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">task_alt</span>Simpan Status
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Kelola Bahan --}}
<div id="modal-kelola-bahan" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-12 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border z-10">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Kelola Bahan Produksi</h3>
                <p class="text-on-surface-variant font-body-md text-xs mt-1">Pilih bahan dari stok gudang <span id="kb-nomor" class="font-bold text-gold-accent"></span> — stok turun otomatis.</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="form-kelola-bahan" method="POST" class="p-6 space-y-5">
            @csrf
            <div class="space-y-3">
                @forelse ($materials as $material)
                    @php $menipis = $material->stok_sekarang <= $material->minimal_stok; @endphp
                    <label data-bahan-row="{{ $material->production_material_id }}" class="flex items-center gap-3 p-3 border {{ $menipis ? 'border-error/25' : 'border-muted-border' }} rounded-lg hover:border-gold-accent/50 transition-colors cursor-pointer">
                        <input type="checkbox" data-bahan-check="{{ $material->production_material_id }}" class="shrink-0 accent-[#C9A24D]" />
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-on-surface">{{ $material->nama_bahan }} <span class="text-xs text-on-surface-variant font-normal">{{ $material->satuan }}</span></p>
                            <p class="text-xs text-on-surface-variant">Stok tersedia: <span class="font-bold {{ $menipis ? 'text-error' : 'text-on-surface' }}">{{ number_format($material->stok_sekarang, 0, ',', '.') }}</span> • Min. {{ number_format($material->minimal_stok, 0, ',', '.') }}</p>
                        </div>
                        <input type="number" data-bahan-qty="{{ $material->production_material_id }}" name="bahan[{{ $material->production_material_id }}]" min="0" step="any" placeholder="0" class="raliva-input !w-24 text-center" />
                    </label>
                @empty
                    <div class="text-center py-8">
                        <p class="text-on-surface-variant font-body-md text-sm">Belum ada bahan tercatat. Tambahkan lewat menu <span class="font-bold text-on-surface">Bahan Produksi</span>.</p>
                        <a href="{{ route('produksi.bahan-produksi') }}" class="mt-3 inline-block text-sm font-semibold text-gold-accent hover:underline">Buka Bahan Produksi</a>
                    </div>
                @endforelse
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" {{ $materials->isEmpty() ? 'disabled' : '' }} class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">inventory_2</span>Simpan Bahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-modal-open="modal-ubah-status"]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const allowed = JSON.parse(btn.getAttribute('data-statuses') || '[]');
            const select = document.getElementById('ub-select');
            select.innerHTML = '';
            allowed.forEach((status) => {
                const labels = {
                    diproses: 'Diproses', menunggu_qc: 'Menunggu QC', dibatalkan: 'Dibatalkan'
                };
                const opt = document.createElement('option');
                opt.value = status;
                opt.textContent = labels[status] || status;
                select.appendChild(opt);
            });
            select.innerHTML += '<option value="" disabled selected>Pilih status…</option>';
            select.value = '';
            document.getElementById('ub-nomor').textContent = btn.getAttribute('data-nomor');
            document.getElementById('form-ubah-status').action = btn.getAttribute('data-status-url');
        });
    });

    document.querySelectorAll('[data-modal-open="modal-kelola-bahan"]').forEach((btn) => {
        btn.addEventListener('click', () => {
            let alokasi = {};
            try { alokasi = JSON.parse(btn.getAttribute('data-alokasi') || '{}'); } catch (e) {}
            document.querySelectorAll('[data-bahan-row]').forEach((row) => {
                const id = row.getAttribute('data-bahan-row');
                const check = document.querySelector('[data-bahan-check="' + id + '"]');
                const qty = document.querySelector('[data-bahan-qty="' + id + '"]');
                const val = alokasi[id] || '';
                if (check && qty) {
                    qty.value = val;
                    check.checked = val !== '';
                }
            });
            document.getElementById('kb-nomor').textContent = btn.getAttribute('data-nomor');
            document.getElementById('form-kelola-bahan').action = btn.getAttribute('data-bahan-url');
        });
    });

    document.querySelectorAll('[data-bahan-check]').forEach((check) => {
        check.addEventListener('change', () => {
            const qty = document.querySelector('[data-bahan-qty="' + check.getAttribute('data-bahan-check') + '"]');
            if (qty) qty.disabled = !check.checked;
        });
    });
</script>
@endpush