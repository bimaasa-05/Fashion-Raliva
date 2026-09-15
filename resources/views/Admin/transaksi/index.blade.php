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
        <div class="bg-surface-container-high rounded-lg p-5 border border-[var(--border-soft)]">
            <p class="text-on-surface-variant text-xs uppercase tracking-wider">Total Pemasukan</p>
            <p class="text-2xl font-bold text-green-600 mt-1">Rp {{ number_format((float) $stats['total_pemasukan'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-surface-container-high rounded-lg p-5 border border-[var(--border-soft)]">
            <p class="text-on-surface-variant text-xs uppercase tracking-wider">Total Pengeluaran</p>
            <p class="text-2xl font-bold text-error mt-1">Rp {{ number_format((float) $stats['total_pengeluaran'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-surface-container-high rounded-lg p-5 border border-[var(--border-soft)]">
            <p class="text-on-surface-variant text-xs uppercase tracking-wider">Total Bersih</p>
            <p class="text-2xl font-bold {{ $stats['total_bersih'] >= 0 ? 'text-gold-accent' : 'text-error' }} mt-1">Rp {{ number_format((float) $stats['total_bersih'], 0, ',', '.') }}</p>
        </div>
    </section>

    <!-- Tabs -->
    <div class="flex gap-2 border-b border-[var(--border-soft)]">
        <button class="px-4 py-2 rounded-t-lg font-label-sm text-sm border-b-2 border-gold-accent text-gold-accent" onclick="switchTabTransaksi('pemasukan')">Pemasukan</button>
        <button class="px-4 py-2 rounded-t-lg font-label-sm text-sm border-b-2 border-transparent text-on-surface-variant" onclick="switchTabTransaksi('pengeluaran')">Pengeluaran</button>
    </div>

    <!-- Tab: Pemasukan -->
    <div id="tab-pemasukan">
        <div class="bg-surface-container-high rounded-lg p-4 border border-[var(--border-soft)] mb-4">
            <h3 class="font-title-md text-title-md text-on-surface mb-3">Tambah Pemasukan</h3>
            <form method="POST" action="{{ route('admin.transaksi.pemasukan') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                @csrf
                <div>
                    <label class="block text-xs text-on-surface-variant mb-1">Jumlah (Rp) *</label>
                    <input type="number" name="jumlah" required min="0" step="0.01" class="w-full rounded-lg border border-[var(--border-soft)] px-3 py-2 bg-surface text-sm" />
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs text-on-surface-variant mb-1">Keterangan *</label>
                    <input type="text" name="keterangan" required placeholder="cth. Penjualan tunai" class="w-full rounded-lg border border-[var(--border-soft)] px-3 py-2 bg-surface text-sm" />
                </div>
                <div class="sm:col-span-3 flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-deep-onyx text-on-primary text-xs rounded">Simpan</button>
                </div>
            </form>
        </div>

        <div class="bg-surface-container-high rounded-lg border border-[var(--border-soft)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-on-surface-variant border-b border-[var(--border-soft)]">
                            <th class="py-3 px-4">Tanggal</th>
                            <th class="py-3 px-4">Jenis</th>
                            <th class="py-3 px-4">Keterangan</th>
                            <th class="py-3 px-4 text-right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pemasukan as $t)
                            <tr class="border-b border-[var(--border-soft)] hover:bg-surface-container-low/50">
                                <td class="py-3 px-4">{{ $t->created_at?->translatedFormat('d M Y H:i') ?? '-' }}</td>
                                <td class="py-3 px-4">{{ $t->jenis_transaksi }}</td>
                                <td class="py-3 px-4">{{ $t->keterangan ?? '-' }}</td>
                                <td class="py-3 px-4 text-right font-bold text-green-600">Rp {{ number_format((float) $t->jumlah, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-8 text-center text-on-surface-variant">Belum ada pemasukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $pemasukan->withQueryString()->links() }}
        </div>
    </div>

    <!-- Tab: Pengeluaran -->
    <div id="tab-pengeluaran" class="hidden">
        <div class="bg-surface-container-high rounded-lg p-4 border border-[var(--border-soft)] mb-4">
            <h3 class="font-title-md text-title-md text-on-surface mb-3">Tambah Pengeluaran</h3>
            <form method="POST" action="{{ route('admin.transaksi.pengeluaran') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                @csrf
                <div class="sm:col-span-2">
                    <label class="block text-xs text-on-surface-variant mb-1">Nama *</label>
                    <input type="text" name="nama" required placeholder="cth. Beli kain" class="w-full rounded-lg border border-[var(--border-soft)] px-3 py-2 bg-surface text-sm" />
                </div>
                <div>
                    <label class="block text-xs text-on-surface-variant mb-1">Kategori</label>
                    <input type="text" name="kategori" placeholder="Lainnya" class="w-full rounded-lg border border-[var(--border-soft)] px-3 py-2 bg-surface text-sm" />
                </div>
                <div>
                    <label class="block text-xs text-on-surface-variant mb-1">Nominal (Rp) *</label>
                    <input type="number" name="nominal" required min="0" step="0.01" class="w-full rounded-lg border border-[var(--border-soft)] px-3 py-2 bg-surface text-sm" />
                </div>
                <div>
                    <label class="block text-xs text-on-surface-variant mb-1">Tanggal *</label>
                    <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}" class="w-full rounded-lg border border-[var(--border-soft)] px-3 py-2 bg-surface text-sm" />
                </div>
                <div class="sm:col-span-4 flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-deep-onyx text-on-primary text-xs rounded">Simpan</button>
                </div>
            </form>
        </div>

        <div class="bg-surface-container-high rounded-lg border border-[var(--border-soft)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-on-surface-variant border-b border-[var(--border-soft)]">
                            <th class="py-3 px-4">Tanggal</th>
                            <th class="py-3 px-4">Nama</th>
                            <th class="py-3 px-4">Kategori</th>
                            <th class="py-3 px-4 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengeluaran as $e)
                            <tr class="border-b border-[var(--border-soft)] hover:bg-surface-container-low/50">
                                <td class="py-3 px-4">{{ \Illuminate\Support\Str::limit($e->tanggal?->translatedFormat('d M Y') ?? '-', 20) }}</td>
                                <td class="py-3 px-4">{{ $e->nama }}</td>
                                <td class="py-3 px-4">{{ $e->kategori ?? '-' }}</td>
                                <td class="py-3 px-4 text-right font-bold text-error">Rp {{ number_format((float) $e->nominal, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-8 text-center text-on-surface-variant">Belum ada pengeluaran.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $pengeluaran->withQueryString()->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
function switchTabTransaksi(tab) {
    document.getElementById('tab-pemasukan').classList.toggle('hidden', tab !== 'pemasukan');
    document.getElementById('tab-pengeluaran').classList.toggle('hidden', tab !== 'pengeluaran');
    document.querySelectorAll('[onclick^="switchTabTransaksi"]').forEach(btn => {
        if (btn.textContent.trim().toLowerCase() === tab) {
            btn.classList.add('border-gold-accent', 'text-gold-accent');
            btn.classList.remove('border-transparent', 'text-on-surface-variant');
        } else {
            btn.classList.remove('border-gold-accent', 'text-gold-accent');
            btn.classList.add('border-transparent', 'text-on-surface-variant');
        }
    });
}
</script>
@endpush
@endsection
