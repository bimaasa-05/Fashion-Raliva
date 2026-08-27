@extends('layouts.gudang')

@section('title', 'Riwayat Stok')

@section('header-title', 'Riwayat Stok')
@section('header-badge', 'Gudang Utama Bandung')
@section('header-subtitle', 'Jejak audit seluruh perubahan stok pada gudang Anda.')

@section('content')
@php
    $rows = [
        ['22 Agu 2026 • 14:05', 'Basic T-Shirt Cotton', 'Stok Rusak', 145, -3, 142, 'SR-0008', 'Budi Santoso'],
        ['22 Agu 2026 • 13:40', 'Silk Scarf', 'Pemeriksaan', 7, -2, 5, 'PS-0012', 'Andi Pratama'],
        ['22 Agu 2026 • 11:20', 'Pleated Skirt', 'Pemindahan', 57, -30, 27, 'PM-0004', 'Andi Pratama'],
        ['22 Agu 2026 • 10:15', 'Knit Cardigan Rajut', 'Barang Keluar', 54, -20, 34, 'BK-0008', 'Budi Santoso'],
        ['22 Agu 2026 • 09:32', 'Hoodie Fleece Premium', 'Barang Masuk', 38, 25, 63, 'BM-0009', 'Citra Dewi'],
        ['21 Agu 2026 • 15:42', 'Midi Dress Linen', 'Barang Keluar', 66, -8, 58, 'BK-0006', 'Citra Dewi'],
        ['21 Agu 2026 • 09:30', 'Relaxed Blazer', 'Barang Keluar', 58, -12, 46, 'BK-0005', 'Andi Pratama'],
        ['20 Agu 2026 • 16:00', 'Cargo Shorts', 'Pemeriksaan', 45, -4, 41, 'PS-0014', 'Citra Dewi'],
        ['19 Agu 2026 • 14:30', 'Straight Fit Pants', 'Penyesuaian', 15, -3, 12, 'ADJ-0021', 'Andi Pratama'],
        ['18 Agu 2026 • 10:00', 'Denim Jacket Classic', 'Barang Keluar', 8, -8, 0, 'BK-0009', 'Citra Dewi'],
    ];
    $aktivitasIcon = [
        'Barang Masuk' => 'archive',
        'Barang Keluar' => 'unarchive',
        'Pemindahan' => 'swap_horiz',
        'Pemeriksaan' => 'fact_check',
        'Stok Rusak' => 'report',
        'Penyesuaian' => 'tune',
    ];
@endphp

<div data-skeleton class="space-y-section-gap">
    <div class="h-16 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-[420px] bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap" data-table-scope>
    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 md:p-6 card-premium">
        <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 mb-6">
            <div class="flex items-center gap-2 mb-3">
                <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
                <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter Riwayat</span>
            </div>
            <div class="flex flex-wrap items-end gap-gutter">
            <div class="flex flex-col gap-2">
                <label class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Dari Tanggal</label>
                <input type="date" class="bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent" />
            </div>
            <div class="flex flex-col gap-2">
                <label class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Sampai Tanggal</label>
                <input type="date" class="bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent" />
            </div>
            <div class="flex flex-col gap-2 min-w-[160px]">
                <label class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Produk</label>
                <select data-table-filter="produk" class="bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent">
                    <option value="semua">Semua Produk</option>
                    <option value="basic">Basic T-Shirt Cotton</option>
                    <option value="silk">Silk Scarf</option>
                    <option value="pleated">Pleated Skirt</option>
                    <option value="knit">Knit Cardigan Rajut</option>
                    <option value="hoodie">Hoodie Fleece Premium</option>
                </select>
            </div>
            <div class="flex flex-col gap-2 min-w-[150px]">
                <label class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Aktivitas</label>
                <select data-table-filter="aktivitas" class="bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent">
                    <option value="semua">Semua Aktivitas</option>
                    <option value="barang-masuk">Barang Masuk</option>
                    <option value="barang-keluar">Barang Keluar</option>
                    <option value="pemindahan">Pemindahan</option>
                    <option value="pemeriksaan">Pemeriksaan</option>
                    <option value="stok-rusak">Stok Rusak</option>
                    <option value="penyesuaian">Penyesuaian</option>
                </select>
            </div>
            <div class="flex flex-col gap-2 min-w-[150px]">
                <label class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Petugas</label>
                <select data-table-filter="petugas" class="bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent">
                    <option value="semua">Semua Petugas</option>
                    <option value="andi">Andi Pratama</option>
                    <option value="budi">Budi Santoso</option>
                    <option value="citra">Citra Dewi</option>
                </select>
            </div>
            <button type="button" data-filter-reset class="px-4 py-2 border border-muted-border rounded-lg font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant hover:text-on-surface hover:border-gold-accent transition-colors">Reset</button>
            </div>
        </div>

        <div data-table-wrap class="overflow-x-auto">
            <table class="w-full min-w-[1000px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">Waktu</th>
                        <th class="p-4 text-left">Produk</th>
                        <th class="p-4 text-left">Aktivitas</th>
                        <th class="p-4 text-center">Sebelum</th>
                        <th class="p-4 text-center">Perubahan</th>
                        <th class="p-4 text-center">Sesudah</th>
                        <th class="p-4 text-center">Referensi</th>
                        <th class="p-4 text-center">Petugas</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @foreach ($rows as $row)
                        @php
                            $change = $row[4];
                            $produkKey = collect(['Basic T-Shirt Cotton' => 'basic', 'Silk Scarf' => 'silk', 'Pleated Skirt' => 'pleated', 'Knit Cardigan Rajut' => 'knit', 'Hoodie Fleece Premium' => 'hoodie'])->get($row[1], 'lainnya');
                            $petugasKey = explode(' ', $row[7])[0];
                            $isMasuk = $change > 0;
                        @endphp
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors" data-table-row data-produk="{{ $produkKey }}" data-aktivitas="{{ str_replace(' ', '-', strtolower($row[2])) }}" data-petugas="{{ strtolower($petugasKey) }}">
                            <td class="p-4 text-on-surface-variant whitespace-nowrap">{{ $row[0] }}</td>
                            <td class="p-4"><span class="text-on-surface">{{ $row[1] }}</span></td>
                            <td class="p-4">
                                <span class="inline-flex items-center gap-1.5 whitespace-nowrap text-on-surface">
                                    <span class="material-symbols-outlined text-[16px] {{ $row[2] === 'Stok Rusak' ? 'text-error' : ($row[2] === 'Barang Masuk' ? 'text-secondary' : 'text-on-surface-variant') }}">{{ $aktivitasIcon[$row[2]] }}</span>
                                    {{ $row[2] }}
                                </span>
                            </td>
                            <td class="p-4 text-center text-on-surface-variant">{{ $row[3] }}</td>
                            <td class="p-4 text-center font-bold {{ $isMasuk ? 'text-secondary' : 'text-error' }}">{{ $isMasuk ? '+' . $change : $change }}</td>
                            <td class="p-4 text-center font-bold text-on-surface">{{ $row[5] }}</td>
                            <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">{{ $row[6] }}</span></td>
                            <td class="p-4 text-center text-on-surface whitespace-nowrap">{{ $row[7] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div data-empty-state class="hidden flex-col items-center justify-center py-16 text-center gap-3">
            <div class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center">
                <span class="material-symbols-outlined text-on-surface-variant">history</span>
            </div>
            <p class="font-title-md text-title-md text-on-surface">Belum Ada Riwayat</p>
            <p class="text-on-surface-variant font-body-md text-sm max-w-sm">Tidak terdapat aktivitas stok yang sesuai dengan filter yang dipilih pada rentang ini.</p>
            <button type="button" data-filter-reset class="mt-2 px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase tracking-widest rounded btn-premium">Reset Filter</button>
        </div>

        <div data-pagination class="flex flex-wrap items-center justify-between gap-4 mt-6">
            <p class="font-label-sm text-xs text-on-surface-variant">Menampilkan {{ count($rows) }} aktivitas terbaru dari total 1.240 tercatat • Gudang Utama Bandung</p>
            <div class="flex items-center gap-1">
                <button type="button" onclick="showRalivaToast('Halaman demo: hanya 1 halaman data tersedia.', 'info')" class="min-w-[36px] h-9 px-2 rounded border border-muted-border text-sm text-on-surface-variant hover:text-on-surface hover:border-gold-accent transition-colors">
                    <span class="material-symbols-outlined text-[18px] align-middle">chevron_left</span>
                </button>
                <button type="button" class="min-w-[36px] h-9 px-3 rounded bg-deep-onyx text-on-primary text-sm font-bold">1</button>
                <button type="button" onclick="showRalivaToast('Halaman demo: hanya 1 halaman data tersedia.', 'info')" class="min-w-[36px] h-9 px-3 rounded border border-muted-border text-sm text-on-surface-variant hover:text-on-surface hover:border-gold-accent transition-colors">2</button>
                <button type="button" onclick="showRalivaToast('Halaman demo: hanya 1 halaman data tersedia.', 'info')" class="min-w-[36px] h-9 px-3 rounded border border-muted-border text-sm text-on-surface-variant hover:text-on-surface hover:border-gold-accent transition-colors">3</button>
                <button type="button" onclick="showRalivaToast('Halaman demo: hanya 1 halaman data tersedia.', 'info')" class="min-w-[36px] h-9 px-2 rounded border border-muted-border text-sm text-on-surface-variant hover:text-on-surface hover:border-gold-accent transition-colors">
                    <span class="material-symbols-outlined text-[18px] align-middle">chevron_right</span>
                </button>
            </div>
        </div>
    </section>
</div>
@endsection
