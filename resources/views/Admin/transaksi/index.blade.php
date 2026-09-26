@extends('layouts.admin')

@section('title', 'Data Transaksi')

@section('header-title', 'Data Transaksi')
@section('header-badge', 'Keuangan')
@section('header-subtitle', 'Catat pemasukan dan pengeluaran toko.')

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    <!-- Stats -->
    <section class="grid grid-cols-1 sm:grid-cols-3 gap-gutter">
        <div class="bg-surface-container-lowest rounded-lg p-5 border border-muted-border card-premium flex flex-col gap-1 relative overflow-hidden">
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-green-600/15 fill pointer-events-none select-none" aria-hidden="true">trending_up</span>
            <p class="text-on-surface-variant font-label-sm text-[10px] uppercase tracking-wider relative">Total Pemasukan</p>
            <p class="text-2xl font-bold text-green-600 relative">Rp {{ number_format((float) $stats['total_pemasukan'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-surface-container-lowest rounded-lg p-5 border border-muted-border card-premium flex flex-col gap-1 relative overflow-hidden">
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-error/15 fill pointer-events-none select-none" aria-hidden="true">trending_down</span>
            <p class="text-on-surface-variant font-label-sm text-[10px] uppercase tracking-wider relative">Total Pengeluaran</p>
            <p class="text-2xl font-bold text-error relative">Rp {{ number_format((float) $stats['total_pengeluaran'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-surface-container-lowest rounded-lg p-5 border border-muted-border card-premium flex flex-col gap-1 relative overflow-hidden">
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">account_balance_wallet</span>
            <p class="text-on-surface-variant font-label-sm text-[10px] uppercase tracking-wider relative">Total Bersih</p>
            <p class="text-2xl font-bold {{ $stats['total_bersih'] >= 0 ? 'text-gold-accent' : 'text-error' }} relative">Rp {{ number_format((float) $stats['total_bersih'], 0, ',', '.') }}</p>
        </div>
    </section>

    <!-- Tabs -->
    <div class="flex gap-2 border-b border-muted-border">
        <button class="px-4 py-2 rounded-t-lg font-label-sm text-sm border-b-2 border-gold-accent text-gold-accent transition-colors" onclick="switchTabTransaksi('pemasukan')" id="tab-btn-pemasukan">Pemasukan</button>
        <button class="px-4 py-2 rounded-t-lg font-label-sm text-sm border-b-2 border-transparent text-on-surface-variant hover:text-on-surface transition-colors" onclick="switchTabTransaksi('pengeluaran')" id="tab-btn-pengeluaran">Pengeluaran</button>
    </div>

    <!-- Tab: Pemasukan -->
    <div id="tab-pemasukan">
        <div class="bg-surface-container-lowest rounded-lg p-5 border border-muted-border card-premium mb-4">
            <h3 class="font-title-md text-title-md text-on-surface mb-4 premium-heading">Tambah Pemasukan</h3>
            <form method="POST" action="{{ route('admin.transaksi.pemasukan') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                @csrf
                <div>
                    <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Jumlah (Rp) <span class="text-error">*</span></label>
                    <input type="number" name="jumlah" required min="0" step="0.01" class="raliva-input w-full" placeholder="0" />
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Kategori <span class="text-error">*</span></label>
                    <select name="kategori" required class="raliva-select w-full">
                        @foreach (($kategoriPemasukan ?? ['Penjualan', 'Investor', 'Modal', 'Komisi', 'Lainnya']) as $kat)
                            <option value="{{ $kat }}">{{ $kat }}</option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-on-surface-variant mt-1">Investor/Modal = omzet saja (tidak masuk saldo tarik).</p>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Keterangan <span class="text-error">*</span></label>
                    <input type="text" name="keterangan" required placeholder="cth. Penjualan tunai" class="raliva-input w-full" />
                </div>
                <div class="sm:col-span-3 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Simpan Pemasukan</button>
                </div>
            </form>
        </div>

        <div class="bg-surface-container-lowest rounded-lg border border-muted-border card-premium overflow-hidden">
            <div class="overflow-x-auto hidden md:block">
                <table class="w-full text-sm premium-table">
                    <thead>
                        <tr class="text-left text-on-surface-variant border-b border-muted-border font-label-sm text-[10px] uppercase tracking-wider">
                            <th class="py-3 px-4">Tanggal</th>
                            <th class="py-3 px-4">Jenis</th>
                            <th class="py-3 px-4">Kategori</th>
                            <th class="py-3 px-4">Keterangan</th>
                            <th class="py-3 px-4 text-right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="font-body-md text-sm">
                        @forelse ($pemasukan as $t)
                            <tr class="border-b border-muted-border hover:bg-surface-container-low/50 transition-colors">
                                <td class="py-3 px-4 text-on-surface-variant">{{ $t->created_at?->translatedFormat('d M Y H:i') ?? '-' }}</td>
                                <td class="py-3 px-4"><span class="inline-flex items-center px-2 py-0.5 rounded-full bg-green-600/10 text-green-600 text-[10px] font-bold uppercase border border-green-600/20">{{ $t->jenis_transaksi }}</span></td>
                                <td class="py-3 px-4 text-on-surface">{{ $t->kategori ?? 'Lainnya' }}</td>
                                <td class="py-3 px-4 text-on-surface">{{ $t->keterangan ?? '-' }}</td>
                                <td class="py-3 px-4 text-right font-bold text-green-600">Rp {{ number_format((float) $t->jumlah, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-8 text-center text-on-surface-variant">Belum ada pemasukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $pemasukan->withQueryString()->links() }}
            <div class="md:hidden grid grid-cols-1 gap-gutter">
                @forelse ($pemasukan as $t)
                    <article data-table-row class="bg-surface-container-lowest border border-muted-border rounded-xl p-4 card-premium relative overflow-hidden">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-on-surface">{{ $t->kategori ?? 'Lainnya' }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $t->keterangan ?? '-' }}</p>
                                <p class="text-xs text-on-surface-variant mt-1">{{ $t->created_at?->translatedFormat('d M Y H:i') ?? '-' }}</p>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-green-600/10 text-green-600 text-[10px] font-bold uppercase border border-green-600/20">{{ $t->jenis_transaksi }}</span>
                                <p class="font-bold text-green-600 mt-1.5">Rp {{ number_format((float) $t->jumlah, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="text-on-surface-variant text-sm py-6 text-center">Belum ada pemasukan.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Tab: Pengeluaran -->
    <div id="tab-pengeluaran" class="hidden">
        <div class="bg-surface-container-lowest rounded-lg p-5 border border-muted-border card-premium mb-4">
            <h3 class="font-title-md text-title-md text-on-surface mb-4 premium-heading">Tambah Pengeluaran</h3>
            <form method="POST" action="{{ route('admin.transaksi.pengeluaran') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                @csrf
                <div class="sm:col-span-2">
                    <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Nama <span class="text-error">*</span></label>
                    <input type="text" name="nama" required placeholder="cth. Beli kain" class="raliva-input w-full" />
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Kategori</label>
                    <select name="kategori" class="raliva-select w-full">
                        <option value="Lainnya">Lainnya</option>
                        @foreach (($kategoriPengeluaran ?? []) as $kat)
                            @if ($kat !== 'Lainnya')
                                <option value="{{ $kat }}">{{ $kat }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Nominal (Rp) <span class="text-error">*</span></label>
                    <input type="number" name="nominal" required min="0" step="0.01" class="raliva-input w-full" placeholder="0" />
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Tanggal <span class="text-error">*</span></label>
                    <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}" class="raliva-input w-full" />
                </div>
                <div class="sm:col-span-4 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Simpan Pengeluaran</button>
                </div>
            </form>
        </div>

        <div class="bg-surface-container-lowest rounded-lg border border-muted-border card-premium overflow-hidden">
            <div class="overflow-x-auto hidden md:block">
                <table class="w-full text-sm premium-table">
                    <thead>
                        <tr class="text-left text-on-surface-variant border-b border-muted-border font-label-sm text-[10px] uppercase tracking-wider">
                            <th class="py-3 px-4">Tanggal</th>
                            <th class="py-3 px-4">Nama</th>
                            <th class="py-3 px-4">Kategori</th>
                            <th class="py-3 px-4 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="font-body-md text-sm">
                        @forelse ($pengeluaran as $e)
                            <tr class="border-b border-muted-border hover:bg-surface-container-low/50 transition-colors">
                                <td class="py-3 px-4 text-on-surface-variant">{{ \Illuminate\Support\Str::limit($e->tanggal?->translatedFormat('d M Y') ?? '-', 20) }}</td>
                                <td class="py-3 px-4 text-on-surface">{{ $e->nama }}</td>
                                <td class="py-3 px-4"><span class="inline-flex items-center px-2 py-0.5 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">{{ $e->kategori ?? '-' }}</span></td>
                                <td class="py-3 px-4 text-right font-bold text-error">Rp {{ number_format((float) $e->nominal, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-8 text-center text-on-surface-variant">Belum ada pengeluaran.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $pengeluaran->withQueryString()->links() }}
            <div class="md:hidden grid grid-cols-1 gap-gutter">
                @forelse ($pengeluaran as $e)
                    <article data-table-row class="bg-surface-container-lowest border border-muted-border rounded-xl p-4 card-premium relative overflow-hidden">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-on-surface">{{ $e->nama }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ \Illuminate\Support\Str::limit($e->tanggal?->translatedFormat('d M Y') ?? '-', 20) }}</p>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">{{ $e->kategori ?? '-' }}</span>
                                <p class="font-bold text-error mt-1.5">Rp {{ number_format((float) $e->nominal, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="text-on-surface-variant text-sm py-6 text-center">Belum ada pengeluaran.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function switchTabTransaksi(tab) {
    document.getElementById('tab-pemasukan').classList.toggle('hidden', tab !== 'pemasukan');
    document.getElementById('tab-pengeluaran').classList.toggle('hidden', tab !== 'pengeluaran');
    
    const btnPemasukan = document.getElementById('tab-btn-pemasukan');
    const btnPengeluaran = document.getElementById('tab-btn-pengeluaran');
    
    if (tab === 'pemasukan') {
        btnPemasukan.classList.add('border-gold-accent', 'text-gold-accent');
        btnPemasukan.classList.remove('border-transparent', 'text-on-surface-variant');
        btnPengeluaran.classList.remove('border-gold-accent', 'text-gold-accent');
        btnPengeluaran.classList.add('border-transparent', 'text-on-surface-variant');
    } else {
        btnPengeluaran.classList.add('border-gold-accent', 'text-gold-accent');
        btnPengeluaran.classList.remove('border-transparent', 'text-on-surface-variant');
        btnPemasukan.classList.remove('border-gold-accent', 'text-gold-accent');
        btnPemasukan.classList.add('border-transparent', 'text-on-surface-variant');
    }
}
</script>
@endpush
@endsection
