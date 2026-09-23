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
            <p class="font-bold">Pendapatan karyawan dihitung dari order yang pembayarannya diverifikasi karyawan tersebut.</p>
            <p class="text-on-surface-variant text-xs mt-1">Pengeluaran = refund yang diselesaikan karyawan (hanya refund berstatus selesai) + pengeluaran toko yang dicatatnya. Pengeluaran toko lama tanpa nama pencatat tidak termasuk hitungan per karyawan.</p>
        </div>
    </section>

    {{-- Ringkasan --}}
    <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        @foreach ([['Total Karyawan', count($rows) + 0, 'on-surface', 'groups'], ['Total Pendapatan', 'Rp '.number_format($totals['pendapatan'],0,',','.'), 'secondary', 'payments'], ['Total Pengeluaran', 'Rp '.number_format($totals['pengeluaran'],0,',','.'), 'error', 'receipt_long'], ['Total Bersih', 'Rp '.number_format($totals['bersih'],0,',','.'), ($totals['bersih'] >= 0 ? 'secondary' : 'error'), 'account_balance_wallet']] as $stat)
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
                    <option value="semua" @selected($roleFilter === 'semua')>Semua Role</option>
                    <option value="admin" @selected($roleFilter === 'admin')>Admin Toko</option>
                    <option value="produksi" @selected($roleFilter === 'produksi')>Produksi</option>
                    <option value="gudang" @selected($roleFilter === 'gudang')>Gudang</option>
                </select>
                @php $rkNoStore = ! \App\Support\OwnerContext::currentStore(); @endphp
                <a href="{{ route('owner.rekap-karyawan.export-excel', ['role' => $roleFilter]) }}" @if($rkNoStore) aria-disabled="true" tabindex="-1" title="Ajukan toko dulu" @endif class="flex items-center justify-center gap-2 px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors shrink-0 {{ $rkNoStore ? 'opacity-60 pointer-events-none' : '' }}">
                    <span class="material-symbols-outlined text-[16px]">{{ $rkNoStore ? 'lock' : 'download' }}</span>Excell
                </a>
                <a href="{{ route('owner.rekap-karyawan.export-pdf', ['role' => $roleFilter]) }}" @if($rkNoStore) aria-disabled="true" tabindex="-1" title="Ajukan toko dulu" @endif class="flex items-center justify-center gap-2 px-5 py-2.5 bg-deep-onyx text-on-primary rounded-lg text-xs font-semibold btn-premium shrink-0 {{ $rkNoStore ? 'opacity-60 pointer-events-none' : '' }}">
                    <span class="material-symbols-outlined text-[16px]">{{ $rkNoStore ? 'lock' : 'picture_as_pdf' }}</span>PDF
                </a>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row lg:items-center gap-3 mb-6">
            <div class="relative flex-1 min-w-[220px]">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                <input type="text" placeholder="Cari nama atau email..." data-table-search class="raliva-search" />
            </div>
        </div>

        <div data-table-wrap class="overflow-x-auto">
            <table class="premium-table w-full min-w-[960px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Karyawan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Role</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Pesanan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Pendapatan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Pengeluaran</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Bersih</th>
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
                            <td class="py-3.5 px-4 text-right text-on-surface">{{ number_format($r['pesanan'],0,',','.') }}</td>
                            <td class="py-3.5 px-4 text-right text-secondary">Rp {{ number_format($r['pendapatan'],0,',','.') }}</td>
                            <td class="py-3.5 px-4 text-right text-error">Rp {{ number_format($r['pengeluaran'],0,',','.') }}</td>
                            <td class="py-3.5 px-4 text-right font-bold {{ $r['bersih'] >= 0 ? 'text-secondary' : 'text-error' }}">Rp {{ number_format($r['bersih'],0,',','.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-6 text-center text-on-surface-variant">Belum ada karyawan yang ditugaskan.</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-gold-accent/40 bg-gold-accent/5">
                        <td class="py-3.5 px-4 font-bold text-on-surface">Total Semua Karyawan</td>
                        <td class="py-3.5 px-4"></td>
                        <td class="py-3.5 px-4"></td>
                        <td class="py-3.5 px-4 text-right font-bold text-on-surface">{{ number_format($totals['pesanan'],0,',','.') }}</td>
                        <td class="py-3.5 px-4 text-right font-bold text-secondary">Rp {{ number_format($totals['pendapatan'],0,',','.') }}</td>
                        <td class="py-3.5 px-4 text-right font-bold text-error">Rp {{ number_format($totals['pengeluaran'],0,',','.') }}</td>
                        <td class="py-3.5 px-4 text-right font-bold {{ $totals['bersih'] >= 0 ? 'text-secondary' : 'text-error' }}">Rp {{ number_format($totals['bersih'],0,',','.') }}</td>
                    </tr>
                </tfoot>
            </table>
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
            Rekap hanya mencakup toko Anda ({{ $storeName }}). Urutan berdasarkan pendapatan tertinggi.
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
        const role = this.value;
        if (role === 'semua') { window.location.href = '{{ route("owner.rekap-karyawan") }}'; return; }
        window.location.href = '{{ route("owner.rekap-karyawan") }}?role=' + role;
    });
});
</script>
@endpush