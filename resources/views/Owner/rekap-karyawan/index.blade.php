@extends('layouts.owner')

@section('title', 'Rekap Karyawan')

@section('header-title', 'Rekap Karyawan')
@section('header-subtitle', 'Pendapatan, pengeluaran, dan total kontribusi tiap karyawan di toko Anda.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        @for ($i = 0; $i < 4; $i++)
            <div class="h-28 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="h-96 bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    @if(! \App\Support\OwnerContext::currentStore())
        <div data-no-store-banner class="rounded-lg border border-gold-accent/30 bg-gold-accent/10 px-4 py-3 flex items-start gap-3">
            <span class="material-symbols-outlined text-gold-accent mt-0.5">storefront</span>
            <div>
                <p class="font-bold text-sm">Belum punya toko</p>
                <p class="text-sm text-on-surface-variant mt-1">Silakan <a href="{{ route('owner.pengajuan-toko') }}" class="underline text-gold-accent font-semibold">ajukan toko</a> untuk akses fitur ini.</p>
            </div>
        </div>
    @endif

    <section data-reveal class="rounded-lg border border-gold-accent/30 bg-gold-accent/10 px-4 py-3 flex items-start gap-3">
        <span class="material-symbols-outlined text-gold-accent mt-0.5">info</span>
        <div class="text-sm text-on-surface">
            <p class="font-bold">Tabel berubah mengikuti filter role: Owner (ROI), Admin (CR/AOV/LTV/rating), Produksi (unit/durasi/keberhasilan), Gudang (transfer/stok).</p>
            <p class="text-on-surface-variant text-xs mt-1">Closing Rate = pesanan selesai / seluruh order yang pernah ditangani karyawan. LTV = pendapatan / customer unik yang ditangani. Rating Admin adalah proxy dari ulasan pada order yang pembayarannya diverifikasi karyawan tersebut. ROI = laba bersih / total investasi (kategori Modal, Investor, dan biaya iklan).</p>
        </div>
    </section>

    {{-- Ringkasan --}}
    @php
        $roiLbl = $ringkasan && $ringkasan['roi'] !== null ? number_format($ringkasan['roi'], 2, ',', '.').'%' : '—';
        $ltvLbl = $ringkasan ? 'Rp '.number_format($ringkasan['ltv'] ?? 0, 0, ',', '.') : 'Rp 0';
        $kartuKeempat = match ($roleFilter) {
            'admin' => ['Rata-rata CR', ($totals['cr'] ?? null) !== null ? number_format($totals['cr'], 2, ',', '.').'%' : '—', 'secondary', 'percent'],
            'produksi' => ['Keberhasilan', ($totals['sukses_persen'] ?? null) !== null ? number_format($totals['sukses_persen'], 2, ',', '.').'%' : '—', 'secondary', 'precision_manufacturing'],
            'gudang' => ['Akurasi Opname', ($totals['akurasi_persen'] ?? null) !== null ? number_format($totals['akurasi_persen'], 2, ',', '.').'%' : '—', 'secondary', 'inventory'],
            default => ['Total Bersih', 'Rp '.number_format($totals['bersih'] ?? 0,0,',','.'), (($totals['bersih'] ?? 0) >= 0 ? 'secondary' : 'error'), 'account_balance_wallet'],
        };
    @endphp
    <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        @foreach ([['Total Karyawan', count($rows) + 0, 'on-surface', 'groups'], ['ROI Toko', $roiLbl, 'secondary', 'trending_up'], ['LTV Pelanggan', $ltvLbl, 'secondary', 'loyalty'], $kartuKeempat] as $stat)
            <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">{{ $stat[0] }}</span>
                <span class="raliva-figure text-[26px] text-{{ $stat[2] }}">{{ $stat[1] }}</span>
                <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">{{ $stat[3] }}</span>
            </div>
        @endforeach
    </section>

    {{-- Tabel Rekap --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="font-title-md text-title-md text-on-surface premium-heading">Rekap per Karyawan</h2>
                <p class="text-xs text-on-surface-variant mt-1">Toko: {{ $storeName }}</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <select data-role-filter class="raliva-select">
                    <option value="owner" @selected($roleFilter === 'owner')>Owner</option>
                    <option value="admin" @selected($roleFilter === 'admin')>Admin Toko</option>
                    <option value="produksi" @selected($roleFilter === 'produksi')>Produksi</option>
                    <option value="gudang" @selected($roleFilter === 'gudang')>Gudang</option>
                </select>
                @php
                    $rkNoStore = ! \App\Support\OwnerContext::currentStore();
                    $rkParams = array_filter(['role' => $roleFilter, 'dari' => $dari ?? null, 'sampai' => $sampai ?? null]);
                @endphp
                <a href="{{ route('owner.rekap-karyawan.export-excel', $rkParams) }}" @if($rkNoStore) aria-disabled="true" tabindex="-1" title="Ajukan toko dulu" @endif class="flex items-center justify-center gap-2 px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors shrink-0 {{ $rkNoStore ? 'opacity-60 pointer-events-none' : '' }}">
                    <span class="material-symbols-outlined text-[16px]">{{ $rkNoStore ? 'lock' : 'download' }}</span>Excell
                </a>
                <a href="{{ route('owner.rekap-karyawan.export-pdf', $rkParams) }}" @if($rkNoStore) aria-disabled="true" tabindex="-1" title="Ajukan toko dulu" @endif class="flex items-center justify-center gap-2 px-5 py-2.5 bg-deep-onyx text-on-primary rounded-lg text-xs font-semibold btn-premium shrink-0 {{ $rkNoStore ? 'opacity-60 pointer-events-none' : '' }}">
                    <span class="material-symbols-outlined text-[16px]">{{ $rkNoStore ? 'lock' : 'picture_as_pdf' }}</span>PDF
                </a>
            </div>
        </div>

        <form method="GET" action="{{ route('owner.rekap-karyawan') }}" class="flex flex-col lg:flex-row lg:items-end gap-3 mb-6">
            <input type="hidden" name="role" value="{{ $roleFilter }}" />
            <div class="relative flex-1 min-w-[220px]">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                <input type="text" placeholder="Cari nama atau email..." data-table-search class="raliva-search" />
            </div>
            <label class="flex flex-col gap-1 text-xs text-on-surface-variant">Dari
                <input type="date" name="dari" value="{{ $dari ?? '' }}" class="raliva-input py-2" />
            </label>
            <label class="flex flex-col gap-1 text-xs text-on-surface-variant">Sampai
                <input type="date" name="sampai" value="{{ $sampai ?? '' }}" class="raliva-input py-2" />
            </label>
            <button type="submit" class="px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors shrink-0">Terapkan</button>
            @if(!empty($dari) || !empty($sampai))
                <a href="{{ route('owner.rekap-karyawan', ['role' => $roleFilter]) }}" class="px-5 py-2.5 text-xs font-semibold text-on-surface-variant hover:text-on-surface transition-colors shrink-0">Reset</a>
            @endif
        </form>

        <div class="hidden md:block">
        <div data-table-wrap class="overflow-x-auto">
            <table class="premium-table w-full min-w-[960px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Karyawan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Role</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                        @if ($roleFilter === 'owner')
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">ROI</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Pendapatan</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Investasi</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Bersih</th>
                        @elseif ($roleFilter === 'admin')
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">CR</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">AOV</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">LTV</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Rating</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Pesanan</th>
                        @elseif ($roleFilter === 'produksi')
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Ditugaskan</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Rata2 Unit</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Rata2 Durasi</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Berhasil</th>
                        @else
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Transfer</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Rata2 Putaran</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Akurasi</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Rusak</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $r)
                        <tr data-table-row data-role="{{ $r['role'] }}" data-status="{{ $r['status'] }}" class="border-b border-muted-border last:border-0">
                            @php
                                $nm = $r['nama'];
                                $initial = collect(explode(' ', $nm))->map(fn($w)=>mb_substr($w,0,1))->slice(0,2)->implode('');
                                $rlabel = ucfirst($r['role']);
                            @endphp
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-surface-container-high border border-outline-variant flex items-center justify-center shrink-0 font-title-md text-xs text-on-surface">{{ $initial }}</div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-on-surface truncate">{{ $nm }}</p>
                                        <p class="text-xs text-on-surface-variant mt-0.5">{{ $r['email'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full {{ $r['role'] === 'admin' ? 'bg-gold-accent/10 text-gold-accent border-gold-accent/30' : 'bg-surface-container-high text-on-surface-variant border-outline-variant' }} text-[9px] font-bold uppercase border whitespace-nowrap">{{ $rlabel }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if ($r['status'] === 'aktif')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-success/10 text-success text-[10px] font-bold uppercase border border-success/20">Aktif</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Nonaktif</span>
                                @endif
                            </td>
                        @if ($roleFilter === 'owner')
                            <td class="py-3.5 px-4 text-right font-bold text-secondary">{{ $r['roi'] !== null ? number_format($r['roi'], 2, ',', '.').'%' : '—' }}</td>
                            <td class="py-3.5 px-4 text-right text-on-surface">Rp {{ number_format($r['pendapatan'] ?? 0, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-4 text-right text-on-surface">Rp {{ number_format($r['investasi'] ?? 0, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-4 text-right text-on-surface">Rp {{ number_format($r['bersih'] ?? 0, 0, ',', '.') }}</td>
                        @elseif ($roleFilter === 'admin')
                                <td class="py-3.5 px-4 text-right text-on-surface">{{ $r['cr'] !== null ? number_format($r['cr'], 2, ',', '.').'%' : '—' }}</td>
                                <td class="py-3.5 px-4 text-right text-secondary">{{ $r['aov'] !== null ? 'Rp '.number_format($r['aov'], 0, ',', '.') : '—' }}</td>
                                <td class="py-3.5 px-4 text-right text-secondary">{{ $r['ltv'] !== null ? 'Rp '.number_format($r['ltv'], 0, ',', '.') : '—' }}</td>
                                <td class="py-3.5 px-4 text-right text-on-surface">{{ $r['rating'] !== null ? number_format($r['rating'], 1).' ★ ('.$r['rating_count'].')' : '—' }}</td>
                                <td class="py-3.5 px-4 text-right text-on-surface">{{ number_format($r['pesanan'],0,',','.') }}</td>
                            @elseif ($roleFilter === 'produksi')
                                <td class="py-3.5 px-4 text-right text-on-surface">{{ number_format($r['ditugaskan'],0,',','.') }}</td>
                                <td class="py-3.5 px-4 text-right text-on-surface">{{ $r['rata_unit_diminta'] !== null ? number_format($r['rata_unit_diminta'], 1, ',', '.') : '—' }}</td>
                                <td class="py-3.5 px-4 text-right text-on-surface">{{ $r['rata_durasi_jam'] !== null ? number_format($r['rata_durasi_jam'], 1, ',', '.').' jam' : '—' }}</td>
                                <td class="py-3.5 px-4 text-right font-bold text-secondary">{{ $r['sukses_persen'] !== null ? number_format($r['sukses_persen'], 2, ',', '.').'%' : '—' }}</td>
                            @else
                                <td class="py-3.5 px-4 text-right text-on-surface">{{ number_format($r['transfer_diminta'],0,',','.') }} <span class="text-on-surface-variant text-xs">({{ $r['transfer_selesai'] }} ok / {{ $r['transfer_batal'] }} btl)</span></td>
                                <td class="py-3.5 px-4 text-right text-on-surface">{{ $r['rata_putaran_jam'] !== null ? number_format($r['rata_putaran_jam'], 1, ',', '.').' jam' : '—' }}</td>
                                <td class="py-3.5 px-4 text-right font-bold text-secondary">{{ $r['akurasi_persen'] !== null ? number_format($r['akurasi_persen'], 2, ',', '.').'%' : '—' }}</td>
                                <td class="py-3.5 px-4 text-right text-error">{{ number_format($r['kerusakan_qty'],0,',','.') }} <span class="text-on-surface-variant text-xs">({{ $r['kerusakan'] }}x)</span></td>
                            @endif
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-6 text-center text-on-surface-variant">Belum ada karyawan yang ditugaskan.</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    @if ($roleFilter === 'owner')
                        <tr class="border-t-2 border-gold-accent/40 bg-gold-accent/5">
                            <td class="py-3.5 px-4 font-bold text-on-surface">Total ({{ $totals['karyawan'] }} owner)</td>
                            <td class="py-3.5 px-4"></td>
                            <td class="py-3.5 px-4"></td>
                            <td class="py-3.5 px-4 text-right font-bold text-secondary">{{ $totals['roi'] !== null ? number_format($totals['roi'], 2, ',', '.').'%' : '—' }}</td>
                            <td class="py-3.5 px-4 text-right font-bold text-on-surface">Rp {{ number_format($totals['pendapatan'], 0, ',', '.') }}</td>
                            <td class="py-3.5 px-4 text-right font-bold text-on-surface">Rp {{ number_format($totals['investasi'], 0, ',', '.') }}</td>
                            <td class="py-3.5 px-4 text-right font-bold text-on-surface">Rp {{ number_format($totals['bersih'], 0, ',', '.') }}</td>
                        </tr>
                    @elseif ($roleFilter === 'admin')
                        <tr class="border-t-2 border-gold-accent/40 bg-gold-accent/5">
                            <td class="py-3.5 px-4 font-bold text-on-surface">Total ({{ $totals['karyawan'] }} admin)</td>
                            <td class="py-3.5 px-4"></td>
                            <td class="py-3.5 px-4"></td>
                            <td class="py-3.5 px-4 text-right font-bold text-on-surface">{{ $totals['cr'] !== null ? number_format($totals['cr'], 2, ',', '.').'%' : '—' }}</td>
                            <td class="py-3.5 px-4 text-right font-bold text-secondary">{{ $totals['aov'] !== null ? 'Rp '.number_format($totals['aov'], 0, ',', '.') : '—' }}</td>
                            <td class="py-3.5 px-4 text-right font-bold text-secondary">{{ $totals['ltv'] !== null ? 'Rp '.number_format($totals['ltv'], 0, ',', '.') : '—' }}</td>
                            <td class="py-3.5 px-4 text-right font-bold text-on-surface">{{ $totals['rating'] !== null ? number_format($totals['rating'], 1).' ★ ('.$totals['rating_count'].')' : '—' }}</td>
                            <td class="py-3.5 px-4 text-right font-bold text-on-surface">{{ number_format($totals['pesanan'],0,',','.') }}</td>
                        </tr>
                    @elseif ($roleFilter === 'produksi')
                        <tr class="border-t-2 border-gold-accent/40 bg-gold-accent/5">
                            <td class="py-3.5 px-4 font-bold text-on-surface">Total Tim Produksi</td>
                            <td class="py-3.5 px-4"></td>
                            <td class="py-3.5 px-4"></td>
                            <td class="py-3.5 px-4 text-right font-bold text-on-surface">{{ number_format($totals['ditugaskan'],0,',','.') }} <span class="text-on-surface-variant text-xs font-normal">({{ $totals['selesai'] }} selesai)</span></td>
                            <td class="py-3.5 px-4 text-right font-bold text-on-surface">—</td>
                            <td class="py-3.5 px-4 text-right font-bold text-on-surface">—</td>
                            <td class="py-3.5 px-4 text-right font-bold text-secondary">{{ $totals['sukses_persen'] !== null ? number_format($totals['sukses_persen'], 2, ',', '.').'%' : '—' }}</td>
                        </tr>
                    @else
                        <tr class="border-t-2 border-gold-accent/40 bg-gold-accent/5">
                            <td class="py-3.5 px-4 font-bold text-on-surface">Total Tim Gudang</td>
                            <td class="py-3.5 px-4"></td>
                            <td class="py-3.5 px-4"></td>
                            <td class="py-3.5 px-4 text-right font-bold text-on-surface">{{ number_format($totals['transfer_diminta'],0,',','.') }} <span class="text-on-surface-variant text-xs font-normal">({{ $totals['transfer_selesai'] }} selesai)</span></td>
                            <td class="py-3.5 px-4 text-right font-bold text-on-surface">—</td>
                            <td class="py-3.5 px-4 text-right font-bold text-secondary">{{ $totals['akurasi_persen'] !== null ? number_format($totals['akurasi_persen'], 2, ',', '.').'%' : '—' }}</td>
                            <td class="py-3.5 px-4 text-right font-bold text-error">{{ number_format($totals['kerusakan_qty'],0,',','.') }}</td>
                        </tr>
                    @endif
                </tfoot>
            </table>
        </div>
        </div>
        <div class="md:hidden space-y-3">
            @forelse ($rows as $r)
                @php
                    $nm = $r['nama'];
                    $initial = collect(explode(' ', $nm))->map(fn($w)=>mb_substr($w,0,1))->slice(0,2)->implode('');
                    $rlabel = ucfirst($r['role']);
                @endphp
                <article data-table-row data-role="{{ $r['role'] }}" data-status="{{ $r['status'] }}" class="bg-surface-container-lowest border border-muted-border rounded-xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-surface-container-high border border-outline-variant flex items-center justify-center shrink-0 font-title-md text-xs text-on-surface">{{ $initial }}</div>
                        <div class="flex-grow min-w-0">
                            <p class="font-bold text-on-surface truncate">{{ $nm }}</p>
                            <p class="text-xs text-on-surface-variant mt-0.5 truncate">{{ $r['email'] }}</p>
                        </div>
                        @if ($r['status'] === 'aktif')
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-success/10 text-success text-[10px] font-bold uppercase border border-success/20 shrink-0">Aktif</span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20 shrink-0">Nonaktif</span>
                        @endif
                    </div>
                    @if ($roleFilter === 'owner')
                    <div class="grid grid-cols-2 gap-2 mt-3 text-sm border-t border-muted-border pt-3">
                        <div><p class="text-[11px] text-on-surface-variant">ROI</p><p class="font-semibold text-secondary">{{ $r['roi'] !== null ? number_format($r['roi'], 2, ',', '.').'%' : '—' }}</p></div>
                        <div class="text-right"><p class="text-[11px] text-on-surface-variant">Pendapatan</p><p class="font-semibold text-on-surface">Rp {{ number_format($r['pendapatan'] ?? 0, 0, ',', '.') }}</p></div>
                        <div><p class="text-[11px] text-on-surface-variant">Investasi</p><p class="font-semibold text-on-surface">Rp {{ number_format($r['investasi'] ?? 0, 0, ',', '.') }}</p></div>
                        <div class="text-right"><p class="text-[11px] text-on-surface-variant">Bersih</p><p class="font-semibold text-on-surface">Rp {{ number_format($r['bersih'] ?? 0, 0, ',', '.') }}</p></div>
                    </div>
                    @elseif ($roleFilter === 'admin')
                    <div class="grid grid-cols-2 gap-2 mt-3 text-sm border-t border-muted-border pt-3">
                        <div><p class="text-[11px] text-on-surface-variant">Closing Rate</p><p class="font-semibold text-on-surface">{{ $r['cr'] !== null ? number_format($r['cr'], 2, ',', '.').'%' : '—' }}</p></div>
                        <div class="text-right"><p class="text-[11px] text-on-surface-variant">AOV</p><p class="font-semibold text-secondary">{{ $r['aov'] !== null ? 'Rp '.number_format($r['aov'], 0, ',', '.') : '—' }}</p></div>
                        <div><p class="text-[11px] text-on-surface-variant">LTV</p><p class="font-semibold text-secondary">{{ $r['ltv'] !== null ? 'Rp '.number_format($r['ltv'], 0, ',', '.') : '—' }}</p></div>
                        <div class="text-right"><p class="text-[11px] text-on-surface-variant">Rating</p><p class="font-semibold text-on-surface">{{ $r['rating'] !== null ? number_format($r['rating'], 1).' ★' : '—' }}</p></div>
                        <div><p class="text-[11px] text-on-surface-variant">Pesanan</p><p class="font-semibold text-on-surface">{{ number_format($r['pesanan'],0,',','.') }}</p></div>
                    </div>
                    @elseif ($roleFilter === 'produksi')
                    <div class="grid grid-cols-2 gap-2 mt-3 text-sm border-t border-muted-border pt-3">
                        <div><p class="text-[11px] text-on-surface-variant">Ditugaskan</p><p class="font-semibold text-on-surface">{{ number_format($r['ditugaskan'],0,',','.') }} ({{ $r['selesai'] }} selesai)</p></div>
                        <div class="text-right"><p class="text-[11px] text-on-surface-variant">Keberhasilan</p><p class="font-semibold text-secondary">{{ $r['sukses_persen'] !== null ? number_format($r['sukses_persen'], 2, ',', '.').'%' : '—' }}</p></div>
                        <div><p class="text-[11px] text-on-surface-variant">Rata2 Unit</p><p class="font-semibold text-on-surface">{{ $r['rata_unit_diminta'] !== null ? number_format($r['rata_unit_diminta'], 1, ',', '.') : '—' }}</p></div>
                        <div class="text-right"><p class="text-[11px] text-on-surface-variant">Rata2 Durasi</p><p class="font-semibold text-on-surface">{{ $r['rata_durasi_jam'] !== null ? number_format($r['rata_durasi_jam'], 1, ',', '.').' jam' : '—' }}</p></div>
                    </div>
                    @else
                    <div class="grid grid-cols-2 gap-2 mt-3 text-sm border-t border-muted-border pt-3">
                        <div><p class="text-[11px] text-on-surface-variant">Transfer</p><p class="font-semibold text-on-surface">{{ number_format($r['transfer_diminta'],0,',','.') }} ({{ $r['transfer_selesai'] }} ok)</p></div>
                        <div class="text-right"><p class="text-[11px] text-on-surface-variant">Akurasi</p><p class="font-semibold text-secondary">{{ $r['akurasi_persen'] !== null ? number_format($r['akurasi_persen'], 2, ',', '.').'%' : '—' }}</p></div>
                        <div><p class="text-[11px] text-on-surface-variant">Mutasi</p><p class="font-semibold text-on-surface">{{ number_format($r['mutasi'],0,',','.') }}</p></div>
                        <div class="text-right"><p class="text-[11px] text-on-surface-variant">Rusak</p><p class="font-semibold text-error">{{ number_format($r['kerusakan_qty'],0,',','.') }}</p></div>
                    </div>
                    @endif
                </article>
            @empty
                <p class="py-6 text-center text-on-surface-variant">Belum ada karyawan yang ditugaskan.</p>
            @endforelse
        </div>

        <div data-empty-state class="hidden flex-col items-center py-12 text-center gap-3">
            <div class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center">
                <span class="material-symbols-outlined text-[28px] text-on-surface-variant">search_off</span>
            </div>
            <p class="text-on-surface-variant font-body-md text-sm">Tidak ada karyawan yang cocok.</p>
            <button type="button" data-filter-reset class="mt-1 px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Reset Filter</button>
        </div>

        <p class="text-xs text-on-surface-variant mt-6 pt-5 border-t border-muted-border flex items-start gap-2">
            <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5 shrink-0">info</span>
            @if ($roleFilter === 'owner')
                ROI = laba bersih / total investasi (kategori Modal, Investor, dan biaya iklan) per pemilik toko.{{ !empty($dari) || !empty($sampai) ? ' Periode: '.($dari ?? 'awal').' s/d '.($sampai ?? 'sekarang').'.' : '' }}
            @elseif ($roleFilter === 'admin')
                CR = pesanan selesai / seluruh order yang pernah ditangani karyawan. LTV = pendapatan / customer unik yang ditangani. Rating adalah proxy dari ulasan pada order yang pembayarannya diverifikasi karyawan tersebut, bukan bukti pelayanan langsung.{{ !empty($dari) || !empty($sampai) ? ' Periode: '.($dari ?? 'awal').' s/d '.($sampai ?? 'sekarang').'.' : '' }}
            @elseif ($roleFilter === 'produksi')
                Metrik dihitung dari production order yang ditugaskan ke karyawan (assigned_to). Durasi hanya dari order selesai yang memiliki tanggal mulai dan selesai valid.{{ !empty($dari) || !empty($sampai) ? ' Periode: '.($dari ?? 'awal').' s/d '.($sampai ?? 'sekarang').'.' : '' }}
            @else
                Transfer diatribusikan ke peminta, bukan penyetuju. Akurasi = opname tanpa selisih / total opname.{{ !empty($dari) || !empty($sampai) ? ' Periode: '.($dari ?? 'awal').' s/d '.($sampai ?? 'sekarang').'.' : '' }}
            @endif
        </p>
    </section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const scope = document.querySelector('[data-table-scope]');
    if (!scope) return;
    const select = scope.querySelector('[data-role-filter]');
    if (!select) return;
    select.addEventListener('change', function () {
        const params = new URLSearchParams(window.location.search);
        params.set('role', this.value);
        params.delete('page');
        window.location.href = '{{ route("owner.rekap-karyawan") }}?' + params.toString();
    });
});
</script>
@endpush