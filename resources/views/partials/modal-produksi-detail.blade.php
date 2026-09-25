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

    $produksiSelesai = ($stepQc !== null) || in_array($o->status, [
        \App\Models\Order::STATUS_MENUNGGU_QC,
        \App\Models\Order::STATUS_SIAP_KIRIM,
        \App\Models\Order::STATUS_DIKIRIM,
        \App\Models\Order::STATUS_SELESAI,
    ], true);

    $durTarget = null;
    $durActual = null;
    $durDiff   = null;

    if ($stepJadwalAwal && $stepJadwalAkhir) {
        $durTarget = (int) $stepJadwalAwal->diffInMinutes($stepJadwalAkhir);
    }
    $endActual = $o->produksi_selesai_pada ?: ($stepQc ?: null);
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
                                    <span>{{ $bahan->nama_bahan }}
                                        @if ($bahan->isDariProduksi())
                                            <span class="text-[10px] uppercase text-secondary">(Produksi)</span>
                                        @else
                                            <span class="text-[10px] uppercase text-on-surface-variant">(Admin)</span>
                                        @endif
                                    </span>
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
                        @if ($stepJadwalAkhir)
                            @if ($produksiSelesai)
                                @if ($stepQc || $o->produksi_selesai_pada)
                                    @php
                                        $selesaiWall = $o->produksi_selesai_pada ?: $stepQc;
                                        $selisih = (int) round(($selesaiWall->timestamp - $stepJadwalAkhir->timestamp) / 60);
                                    @endphp
                                    <p class="text-xs mt-1 {{ $selisih <= 0 ? 'text-secondary' : 'text-error' }}">
                                        @if ($selisih === 0)
                                            Selesai tepat waktu.
                                        @elseif ($selisih < 0)
                                            Selesai lebih cepat {{ $fmtDur(abs($selisih)) }} dari target.
                                        @else
                                            Terlambat {{ $fmtDur($selisih) }} dari target.
                                        @endif
                                    </p>
                                @else
                                    <p class="text-xs text-on-surface-variant mt-1">Produksi selesai · Menunggu QC</p>
                                @endif
                            @else
                                <p class="text-xs text-on-surface-variant mt-1 countdown-badge" data-live-countdown-end="{{ $stepJadwalAkhir->timestamp }}" data-live-countdown-start="{{ $stepJadwalAwal?->timestamp }}">Memuat...</p>
                            @endif
                        @endif
                    </div>
                    <div class="bg-surface-container-low rounded-lg p-3">
                        <p class="text-[10px] uppercase tracking-wider text-on-surface-variant">Aktual (Produksi)</p>
                        <p class="text-on-surface font-bold mt-1"
                           @if ($stepDiterima)
                           data-live-elapsed-start="{{ $stepDiterima->timestamp }}"
                           @if ($stepQc) data-live-elapsed-end="{{ $stepQc->timestamp }}" @endif
                           @endif
                        >{{ $durActual !== null ? $fmtDur($durActual) : '-' }}</p>
                    </div>
                </div>
                @if ($stepDiterima && $durTarget !== null && ($o->produksi_selesai_pada || $stepQc))
                    <div class="mt-3 flex items-center gap-2 rounded-lg border px-3 py-2.5 {{ $durDiff !== null && $durDiff <= 0 ? 'border-secondary/25 bg-secondary-container/10' : 'border-error/25 bg-error/10' }}"
                         data-live-diff
                         data-diff-start="{{ $stepDiterima->timestamp }}"
                         data-diff-end="{{ $o->produksi_selesai_pada?->timestamp ?? $stepQc?->timestamp ?? '' }}"
                         data-diff-target-sec="{{ $durTarget !== null ? $durTarget * 60 : 0 }}">
                        <span class="material-symbols-outlined text-[18px] {{ $durDiff !== null && $durDiff <= 0 ? 'text-secondary' : 'text-error' }}">{{ $durDiff !== null && $durDiff <= 0 ? 'check_circle' : 'error' }}</span>
                        <span class="text-xs {{ $durDiff !== null && $durDiff <= 0 ? 'text-secondary' : 'text-error' }}">
                            @if ($durDiff !== null && $durDiff === 0)
                                Selesai tepat waktu.
                            @elseif ($durDiff !== null && $durDiff < 0)
                                Selesai lebih cepat {{ $fmtDur(abs($durDiff)) }} dari target.
                            @elseif ($durDiff !== null)
                                Terlambat {{ $fmtDur($durDiff) }} dari target.
                            @else
                                Memuat...
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

                    @if ($o->produksi_selesai_pada)
                    <li class="pl-6 relative">
                        <span class="absolute -left-[22px] top-0"><span class="w-9 h-9 rounded-full border border-secondary/25 bg-secondary-container/20 flex items-center justify-center"><span class="material-symbols-outlined text-[18px] text-secondary">handyman</span></span></span>
                        <p class="text-on-surface font-bold">Produksi Selesai</p>
                        <p class="text-xs text-on-surface-variant mt-0.5">{{ $o->produksi_selesai_pada->translatedFormat('d M Y H:i') }}</p>
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
    <script>
        function liveDurFmt(totalSec) {
            if (totalSec === null || isNaN(totalSec) || totalSec < 0) totalSec = 0;
            const h = Math.floor(totalSec / 86400);
            const j = Math.floor((totalSec % 86400) / 3600);
            const m = Math.floor((totalSec % 3600) / 60);
            const s = Math.floor(totalSec % 60);
            const parts = [];
            if (h > 0) parts.push(h + 'h');
            if (j > 0) parts.push(j + 'j');
            if (m > 0) parts.push(m + 'm');
            if (s > 0) parts.push(s + 'd');
            return parts.length ? parts.join(' ') : '< 1 menit';
        }

        function tickLiveDurations() {
            const now = Date.now();

            // Countdown target (admin)
            document.querySelectorAll('[data-live-countdown-end]').forEach(function (el) {
                const end = parseInt(el.dataset.liveCountdownEnd, 10) * 1000;
                const startRaw = el.dataset.liveCountdownStart;
                const start = startRaw ? parseInt(startRaw, 10) * 1000 : null;
                if (start && now < start) {
                    el.textContent = 'Mulai dalam ' + liveDurFmt(Math.floor((start - now) / 1000));
                    el.classList.add('text-secondary');
                    el.classList.remove('text-error', 'font-bold', 'text-on-surface-variant');
                    return;
                }
                const diff = Math.floor((end - now) / 1000);
                if (diff < 0) {
                    el.textContent = 'Terlambat ' + liveDurFmt(Math.abs(diff));
                    el.classList.add('text-error', 'font-bold');
                    el.classList.remove('text-on-surface-variant', 'text-secondary');
                } else {
                    el.textContent = 'Sisa ' + liveDurFmt(diff);
                    el.classList.add('text-on-surface-variant');
                    el.classList.remove('text-error', 'font-bold', 'text-secondary');
                }
            });

            // Elapsed aktual (produksi)
            document.querySelectorAll('[data-live-elapsed-start]').forEach(function (el) {
                const start = parseInt(el.dataset.liveElapsedStart, 10) * 1000;
                const endRaw = el.dataset.liveElapsedEnd;
                const end = endRaw ? parseInt(endRaw, 10) * 1000 : null;
                const base = end ? end : now;
                el.textContent = liveDurFmt(Math.floor((base - start) / 1000));
            });

            // Banner diff live
            document.querySelectorAll('[data-live-diff]').forEach(function (el) {
                const start = parseInt(el.dataset.diffStart, 10) * 1000;
                const endRaw = el.dataset.diffEnd;
                const end = endRaw ? parseInt(endRaw, 10) * 1000 : null;
                const targetSec = parseInt(el.dataset.diffTargetSec, 10) || 0;
                const base = end ? end : now;
                const actualSec = Math.floor((base - start) / 1000);
                const diff = actualSec - targetSec;

                const icon = el.querySelector('.material-symbols-outlined');
                const label = el.querySelector('span.text-xs');
                if (diff === 0) {
                    el.className = el.className.replace(/border-(error|secondary)\/[0-9]+/g, 'border-secondary/25').replace(/bg-(error|secondary-container)\/[0-9]+/g, 'bg-secondary-container/10');
                    if (icon) { icon.textContent = 'check_circle'; icon.className = icon.className.replace(/text-(error|secondary)/g, 'text-secondary'); }
                    if (label) { label.textContent = 'Selesai tepat waktu.'; label.className = label.className.replace(/text-(error|secondary)/g, 'text-secondary'); }
                } else if (diff < 0) {
                    el.className = el.className.replace(/border-(error|secondary)\/[0-9]+/g, 'border-secondary/25').replace(/bg-(error|secondary-container)\/[0-9]+/g, 'bg-secondary-container/10');
                    if (icon) { icon.textContent = 'check_circle'; icon.className = icon.className.replace(/text-(error|secondary)/g, 'text-secondary'); }
                    if (label) { label.textContent = 'Selesai lebih cepat ' + liveDurFmt(Math.abs(diff)) + ' dari target.'; label.className = label.className.replace(/text-(error|secondary)/g, 'text-secondary'); }
                } else {
                    el.className = el.className.replace(/border-(error|secondary)\/[0-9]+/g, 'border-error/25').replace(/bg-(error|secondary-container)\/[0-9]+/g, 'bg-error/10');
                    if (icon) { icon.textContent = 'error'; icon.className = icon.className.replace(/text-(error|secondary)/g, 'text-error'); }
                    if (label) { label.textContent = 'Terlambat ' + liveDurFmt(diff) + ' dari target.'; label.className = label.className.replace(/text-(error|secondary)/g, 'text-error'); }
                }
            });
        }
        setInterval(tickLiveDurations, 1000);
        document.addEventListener('DOMContentLoaded', tickLiveDurations);
    </script>
@endif