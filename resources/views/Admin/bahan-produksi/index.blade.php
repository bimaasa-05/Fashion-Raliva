@extends('layouts.admin')

@section('title', 'Bahan Produksi')

@section('header-title', 'Bahan Produksi')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Kelola stok bahan baku untuk produksi.')

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    <!-- Stats -->
    <section class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        <div class="bg-surface-container-high rounded-lg p-4 border border-[var(--border-soft)]">
            <p class="text-on-surface-variant text-xs uppercase tracking-wider">Total Bahan</p>
            <p class="text-2xl font-bold text-on-surface mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-surface-container-high rounded-lg p-4 border border-[var(--border-soft)]">
            <p class="text-on-surface-variant text-xs uppercase tracking-wider">Aktif</p>
            <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['aktif'] }}</p>
        </div>
        <div class="bg-surface-container-high rounded-lg p-4 border border-[var(--border-soft)]">
            <p class="text-on-surface-variant text-xs uppercase tracking-wider">Stok Menipis</p>
            <p class="text-2xl font-bold text-error mt-1">{{ $stats['menipis'] }}</p>
        </div>
    </section>

    <!-- Form Tambah -->
    <section class="bg-surface-container-high rounded-lg p-4 border border-[var(--border-soft)]">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-title-md text-title-md text-on-surface">Tambah Bahan Produksi</h3>
            <button type="button" onclick="document.getElementById('form-tambah-bahan').classList.toggle('hidden')" class="px-3 py-1.5 bg-deep-onyx text-on-primary text-xs rounded">+ Tambah</button>
        </div>
        <form id="form-tambah-bahan" method="POST" action="{{ route('admin.bahan-produksi.store') }}" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            @csrf
            <div>
                <label class="block text-xs text-on-surface-variant mb-1">Nama Bahan *</label>
                <input type="text" name="nama_bahan" required class="w-full rounded-lg border border-[var(--border-soft)] px-3 py-2 bg-surface text-sm" />
            </div>
            <div>
                <label class="block text-xs text-on-surface-variant mb-1">Kategori *</label>
                <select name="kategori" required class="w-full rounded-lg border border-[var(--border-soft)] px-3 py-2 bg-surface text-sm">
                    <option value="kain">Kain</option>
                    <option value="aksesoris">Aksesoris</option>
                    <option value="kemasan">Kemasan</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>
            <div>
                <label class="block text-xs text-on-surface-variant mb-1">Satuan *</label>
                <input type="text" name="satuan" required placeholder="meter, pcs, roll" class="w-full rounded-lg border border-[var(--border-soft)] px-3 py-2 bg-surface text-sm" />
            </div>
            <div>
                <label class="block text-xs text-on-surface-variant mb-1">Stok</label>
                <input type="number" name="stok" min="0" value="0" class="w-full rounded-lg border border-[var(--border-soft)] px-3 py-2 bg-surface text-sm" />
            </div>
            <div>
                <label class="block text-xs text-on-surface-variant mb-1">Stok Minimum</label>
                <input type="number" name="stok_minimum" min="0" value="0" class="w-full rounded-lg border border-[var(--border-soft)] px-3 py-2 bg-surface text-sm" />
            </div>
            <div>
                <label class="block text-xs text-on-surface-variant mb-1">Supplier</label>
                <select name="supplier_id" class="w-full rounded-lg border border-[var(--border-soft)] px-3 py-2 bg-surface text-sm">
                    <option value="">— Pilih Supplier —</option>
                    @foreach ($suppliers as $s)
                        <option value="{{ $s->supplier_id }}">{{ $s->nama_supplier }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-on-surface-variant mb-1">Status</label>
                <select name="status" class="w-full rounded-lg border border-[var(--border-soft)] px-3 py-2 bg-surface text-sm">
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-4 py-2 bg-deep-onyx text-on-primary text-xs rounded">Simpan</button>
            </div>
        </form>
    </section>

    <!-- Tabel -->
    <section class="bg-surface-container-high rounded-lg border border-[var(--border-soft)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-on-surface-variant border-b border-[var(--border-soft)]">
                        <th class="py-3 px-4">Nama Bahan</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Stok</th>
                        <th class="py-3 px-4">Min.</th>
                        <th class="py-3 px-4">Supplier</th>
                        <th class="py-3 px-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bahans as $b)
                        <tr class="border-b border-[var(--border-soft)] hover:bg-surface-container-low/50">
                            <td class="py-3 px-4 font-medium">{{ $b->nama_bahan }}</td>
                            <td class="py-3 px-4 capitalize">{{ $b->kategori }}</td>
                            <td class="py-3 px-4 {{ $b->stok <= $b->stok_minimum ? 'text-error font-bold' : '' }}">{{ $b->stok }} {{ $b->satuan }}</td>
                            <td class="py-3 px-4">{{ $b->stok_minimum }} {{ $b->satuan }}</td>
                            <td class="py-3 px-4">{{ $b->supplier?->nama_supplier ?? '-' }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-xs {{ $b->status === 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $b->status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-12 text-center text-on-surface-variant">Belum ada bahan produksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $bahans->withQueryString()->links() }}
    </section>
</div>
@endsection
