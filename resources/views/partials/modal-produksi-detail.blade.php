{{--
    Modal Detail Produksi (timeline) — reusable.
    Dibutuhkan variabel `$o` (App\Models\Order) dengan eager load:
    items, checkout, store, bahanList, shipments, qualityChecks.
--}}
@php
    $qcRow = $o->qualityChecks->first();
    $stepDibuat     = $o->created_at;
    $stepJadwalAwal = $o->tgl_mulai_produksi;
    $stepJadwalAkhir= $o->tgl_berakhir_produksi;
    $stepDiterima   = $o->produksi_dimulai_pada;
    $stepQc         = $o->tanggal_qc;
    $stepKirim      = $o->shipments->first()?->created_at;
    $isSelesai      = $o->status === \App\Models\Order::STATUS_SELESAI;

    $durTarget = null;
    $durActual = null;
    $durDiff   = null;

    if ($stepJadwalAwal && $stepJadwalAkhir) {
        $durTarget = (int) $stepJadwalAwal->diffInMinutes($stepJadwalAkhir);
    }
    $endActual = $stepQc ?: null;
    if (! $endActual && $stepDiterima && $o->status === \App\Models\Order::STATUS_DIPROSES) {
        $endActual = now();
    }
    if ($stepDiterima && $endActual) {
        $durActual = (int) $stepDiterima->diffInMinutes($endActual);
        if ($durTarget !== null) {
            $durDiff = $durActual - $durTarget;
        }
    }

    $fmtDur = function (int $menit): string {
        $hari  = intdiv($menit, 1440);
        $sisa  = $menit % 1440;
        $jam   = intdiv($sisa, 60);
        $mnt   = $sisa % 60;
        $part  = [];
        if ($hari > 0)  $part[] = $hari . 'h';
        if ($jam > 0)   $part[] = $jam . 'j';
        if ($mnt > 0)   $part[] = $mnt . 'm';
        return $part ? implode(' ', $part) : '< 1 menit';
    };

    $stepBadge = function (string $warna, string $ikon): string {
        return '<div class="shrink-0 w-9 h-9 rounded-full flex items-center justify-center border ' . $warna . '"><span class="material-symbols-outlined text-[18px] ' . $warna . '">' . $ikon . '</span></div>';
    };
@endphp
<div id="modal-detail-produksi-{{ $o->order_id }}" class="hidden fixed inset-0 z-[80] flex items-center justify-center p-4" data-modal-produksi>
    <div class="absolute inset-0 bg-black/50" onclick="closeDetailProduksi('{{ $o->order_id }}')"></div>
    <div class="relative mx-auto w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl max-h-[85vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface">Detail Produksi</h3>
                <p class="text-on-surface-variant font-mono text-xs uppercase tracking-wider mt-1">{{ $o->nomor_order }}</p>
            </div>
            <button type="button" onclick="closeDetailProduksi('{{ $o->order_id }}')" class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>

        <div class="p-6 space-y-5 font-body-md text-sm">
            {{-- Info dasar pesanan --}}
            <div class="bg-surface-container-low rounded-lg p-4 space-y-2">
                <div class="flex justify-between gap-4"><span class="text-on-surface-variant">Pelanggan</span><span class="text-on-surface text-right">{{ $o->checkout?->nama_penerima ?? $o->checkout?->user?->nama_lengkap ?? '-' }}</span></div>
                <div class="flex justify-between gap-4"><span class="text-on-surface-variant">Toko</span><span class="text-on-surface text-right">{{ $o->store?->nama_toko ?? '-' }}</span></div>
                <div class="flex justify-between gap-4"><span class="text-on-surface-variant">Status</span><span class="text-on-surface text-right capitalize">{{ str_replace('_', ' ', $o->status) }}</span></div>
                <div>
                    <p class="text-on-surface-variant mb-1">Produk &amp; Jumlah</p>
                    <ul class="space-y-1">
                        @forelse ($o->items as $item)
                            <li class="flex justify-between gap-3 text-on-surface">
                                <span>{{ $item->nama_produk_snapshot }}</span>
                                <span class="text-on-surface-variant shrink-0">× {{ $item->quantity }}</span>
                            </li>
                        @empty
                            <li class="text-on-surface-variant text-xs">Tidak ada item.</li>
                        @endforelse
                    </ul>
                </div>
                @if ($o->bahanList->isNotEmpty())
                    <div>
                        <p class="text-on-surface-variant mb-1">Bahan Produksi</p>
                        <ul class="space-y-1">
                            @foreach ($o->bahanList as $bahan)
                                <li class="flex justify-between gap-3 text-on-surface">
                                    <span>{{ $bahan->nama_bahan }}</span>
                                    <span class="text-on-surface-variant shrink-0">{{ $bahan->jumlah }} {{ $bahan->satuan }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if ($qcRow)
                    <div class="flex justify-between gap-4 pt-2 border-t border-muted-border">
                        <span class="text-on-surface-variant">Hasil QC</span>
                        <span class="text-on-surface text-right"><span class="text-secondary font-bold">{{ $qcRow->jumlah_lulus }}</span> lulus • <span class="text-error">{{ $qcRow->jumlah_gagal }}</span> gagal</span>
                    </div>
                @endif
            </div>

            {{-- Perbandingan durasi --}}
            <div>
                <p class="text-[10px] uppercase tracking-widest text-on-surface-variant mb-2">Durasi Pengerjaan</p>
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-surface-container-low rounded-lg p-3">
                        <p class="text-[10px] uppercase tracking-wider text-on-surface-variant">Target (Admin)</p>
                        <p class="text-on-surface font-bold mt-1">{{ $durTarget !== null ? $fmtDur($durTarget) : '-' }}</p>
                    </div>
                    <div class="bg-surface-container-low rounded-lg p-3">
                        <p class="text-[10px] uppercase tracking-wider text-on-surface-variant">Aktual (Produksi)</p>
                        <p class="text-on-surface font-bold mt-1">{{ $durActual !== null ? $fmtDur($durActual) : '-' }}</p>
                    </div>
                </div>
                @if ($durDiff !== null)
                    <div class="mt-3 flex items-center gap-2 rounded-lg border px-3 py-2.5 {{ $durDiff <= 0 ? 'border-secondary/25 bg-secondary-container/10' : 'border-error/25 bg-error/10' }}">
                        <span class="material-symbols-outlined text-[18px] {{ $durDiff <= 0 ? 'text-secondary' : 'text-error' }}">{{ $durDiff <= 0 ? 'check_circle' : 'error' }}</span>
                        <span class="text-xs {{ $durDiff <= 0 ? 'text-secondary' : 'text-error' }}">
                            @if ($durDiff === 0)
                                Selesai tepat waktu.
                            @elseif ($durDiff < 0)
                                Selesai lebih cepat {{ $fmtDur(abs($durDiff)) }} dari target.
                            @else
                                Terlambat {{ $fmtDur($durDiff) }} dari target.
                            @endif
                        </span>
                    </div>
                @endif
            </div>

            {{-- Timeline --}}
            <div>
                <p class="text-[10px] uppercase tracking-widest text-on-surface-variant mb-3">Timeline Pengerjaan</p>
                <ol class="relative space-y-5 border-l-2 border-muted-border ml-[18px]">
                    @if ($stepDibuat)
                    <li class="pl-6 relative">
                        <span class="absolute -left-[22px] top-0">{!! $stepBadge('bg-gold-accent/15 text-gold-accent border-gold-accent/30', 'shopping_bag') !!}</span>
                        <p class="text-on-surface font-bold">Pesanan Dibuat</p>
                        <p class="text-xs text-on-surface-variant mt-0.5">{{ $stepDibuat->translatedFormat('d M Y H:i') }}</p>
                    </li>
                    @endif

                    @if ($stepJadwalAwal)
                    <li class="pl-6 relative">
                        <span class="absolute -left-[22px] top-0">{!! $stepBadge('bg-surface-container-high text-on-surface-variant border-outline-variant', 'calendar_month') !!}</span>
                        <p class="text-on-surface font-bold">Jadwal Produksi (Admin)</p>
                        <p class="text-xs text-on-surface-variant mt-0.5">
                            {{ $stepJadwalAwal->translatedFormat('d M H:i') }} → {{ $stepJadwalAkhir?->translatedFormat('d M H:i') ?? '-' }}
                        </p>
                    </li>
                    @endif

                    @if ($stepDiterima)
                    <li class="pl-6 relative">
                        <span class="absolute -left-[22px] top-0 {!! $o->status === \App\Models\Order::STATUS_DIPROSES ? 'bg-gold-accent/15 text-gold-accent border-gold-accent/30' : 'bg-secondary-container/20 text-secondary border-secondary/25' !!}">
                            <span class="w-9 h-9 rounded-full border flex items-center justify-center"><span class="material-symbols-outlined text-[18px]">precision_manufacturing</span></span>
                        </span>
                        <p class="text-on-surface font-bold">Produksi Dimulai</p>
                        <p class="text-xs text-on-surface-variant mt-0.5">{{ $stepDiterima->translatedFormat('d M Y H:i') }}</p>
                        @if ($o->produksi_catatan_tolak)
                            <p class="text-xs text-error mt-1">⚠ Ditolak: {{ $o->produksi_catatan_tolak }}</p>
                        @endif
                    </li>
                    @endif

                    @if ($stepQc)
                    <li class="pl-6 relative">
                        <span class="absolute -left-[22px] top-0"><span class="w-9 h-9 rounded-full border border-secondary/25 bg-secondary-container/20 flex items-center justify-center"><span class="material-symbols-outlined text-[18px] text-secondary">verified</span></span></span>
                        <p class="text-on-surface font-bold">QC + Packing</p>
                        <p class="text-xs text-on-surface-variant mt-0.5">{{ $stepQc->translatedFormat('d M Y H:i') }}@if ($o->tanggal_packing) • Packing {{ $o->tanggal_packing->translatedFormat('d M H:i') }}@endif</p>
                    </li>
                    @endif

                    @if ($stepKirim)
                    <li class="pl-6 relative">
                        <span class="absolute -left-[22px] top-0"><span class="w-9 h-9 rounded-full border border-sky-500/25 bg-sky-500/10 flex items-center justify-center"><span class="material-symbols-outlined text-[18px] text-sky-600">local_shipping</span></span></span>
                        <p class="text-on-surface font-bold">Dikirim</p>
                        <p class="text-xs text-on-surface-variant mt-0.5">{{ $stepKirim->translatedFormat('d M Y H:i') }}</p>
                    </li>
                    @endif

                    @if ($isSelesai)
                    <li class="pl-6 relative">
                        <span class="absolute -left-[22px] top-0"><span class="w-9 h-9 rounded-full border border-secondary/25 bg-secondary-container/20 flex items-center justify-center"><span class="material-symbols-outlined text-[18px] text-secondary">check_circle</span></span></span>
                        <p class="text-on-surface font-bold">Selesai</p>
                        <p class="text-xs text-on-surface-variant mt-0.5">Pesanan diterima customer.</p>
                    </li>
                    @endif
                </ol>
            </div>
        </div>

        <div class="sticky bottom-0 bg-surface-container-lowest border-t border-muted-border p-4 flex justify-end gap-3">
            <button type="button" onclick="closeDetailProduksi('{{ $o->order_id }}')" class="px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Tutup</button>
        </div>
    </div>
</div>

@if (! function_exists('detailProduksiAssetsRendered'))
    @php
        function detailProduksiAssetsRendered(): bool { return true; }
    @endphp
    <script>
        function openDetailProduksi(orderId) {
            const modal = document.getElementById('modal-detail-produksi-' + orderId);
            if (!modal) return;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeDetailProduksi(orderId) {
            const modal = document.getElementById('modal-detail-produksi-' + orderId);
            if (modal) modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
        document.addEventListener('keydown', function (e) {
            if (e.key !== 'Escape') return;
            document.querySelectorAll('[data-modal-produksi]:not(.hidden)').forEach(function (m) {
                m.classList.add('hidden');
                document.body.style.overflow = '';
            });
        });
    </script>
@endif