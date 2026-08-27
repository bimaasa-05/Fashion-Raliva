@extends('layouts.owner')

@section('title', 'Kelola Slot')

@section('header-title', 'Kelola Slot')
@section('header-badge', '142 / 200 Terpakai')
@section('header-subtitle', 'Kelola kuota slot produk toko Anda — tambah slot via Super Admin.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="h-44 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-section-gap">
        <div class="lg:col-span-2 h-96 bg-surface-container-high rounded-lg animate-pulse"></div>
        <div class="lg:col-span-3 h-80 bg-surface-container-high rounded-lg animate-pulse"></div>
    </div>
</div>

<div data-real class="hidden space-y-section-gap">
    {{-- Kuota Saat Ini --}}
    <section data-reveal class="bg-deep-onyx text-on-primary rounded-lg p-6 md:p-8 relative overflow-hidden">
        <span class="material-symbols-outlined absolute -right-6 -bottom-8 text-[160px] text-on-primary/5 pointer-events-none select-none" aria-hidden="true">storage</span>
        <div class="relative flex flex-col lg:flex-row lg:items-center justify-between gap-8">
            <div>
                <p class="raliva-label text-gold-accent">Kuota Aktif</p>
                <p class="raliva-figure text-[34px] md:text-[42px] mt-2">142 <span class="text-on-primary/50 text-[22px] font-normal">/ 200</span> <span class="text-sm font-normal text-on-primary/60">slot terpakai</span></p>
                <p class="font-body-md text-sm text-inverse-on-surface/60 mt-2">Sisa 58 slot • Kelola penuh oleh SuperAdmin</p>
            </div>
            <div class="w-full max-w-md">
                <div class="h-3 bg-white/10 rounded-full overflow-hidden">
                    <div class="progress-fill h-full rounded-full" data-progress-mode="quota" data-progress="71"></div>
                </div>
                <div class="mt-3 flex items-center justify-between">
                    <span class="text-xs text-inverse-on-surface/60">71% terpakai</span>
                    <span class="text-xs font-bold text-gold-accent">58 tersedia</span>
                </div>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-section-gap items-start">
        {{-- Form Ajukan Tambah Slot --}}
        <section data-reveal class="lg:col-span-2 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium lg:sticky lg:top-24">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading">Ajukan Tambah Slot</h2>
            <p class="text-on-surface-variant font-body-md text-xs mt-1">Permintaan akan diteruskan ke SuperAdmin untuk persetujuan.</p>

            <form data-toast-message="Permintaan tambah slot berhasil diajukan ke SuperAdmin." class="mt-6 space-y-5">
                <div>
                    <label for="slot-jumlah" class="block raliva-label mb-2">Jumlah Slot Tambahan</label>
                    <input id="slot-jumlah" type="number" value="50" min="1" max="500" required class="raliva-input" />
                    <p class="text-xs text-on-surface-variant mt-1.5">Kelipatan 10 disarankan. Maksimal 500 per pengajuan.</p>
                </div>
                <div>
                    <label for="slot-alasan" class="block raliva-label mb-2">Alasan / Catatan</label>
                    <textarea id="slot-alasan" rows="3" placeholder="cth. Menambah koleksi musim baru 40 SKU..." class="raliva-textarea"></textarea>
                </div>
                <div class="border border-gold-accent/20 bg-gold-accent/5 rounded-lg px-4 py-3 flex items-start gap-3">
                    <span class="material-symbols-outlined text-[20px] text-gold-accent mt-0.5">info</span>
                    <p class="text-on-surface-variant font-body-md text-xs leading-relaxed">Sistem paket dihapus. Slot ditambah manual oleh SuperAdmin setelah menyetujui permintaan Anda.</p>
                </div>
                <button type="submit" class="w-full py-3 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">send</span>Ajukan ke SuperAdmin
                </button>
            </form>
        </section>

        {{-- Log Penambahan Slot --}}
        <section data-reveal class="lg:col-span-3 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
            <h2 class="font-title-md text-title-md text-on-surface premium-heading">Riwayat Slot</h2>
            <p class="text-on-surface-variant font-body-md text-xs mt-1">Audit trail penambahan kuota — transparan untuk Owner & SuperAdmin.</p>

            <div data-table-wrap class="overflow-x-auto mt-6">
                <table class="premium-table w-full min-w-[640px] font-body-md text-sm">
                    <thead>
                        <tr class="border-b border-muted-border text-left">
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Tanggal</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Oleh</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Tambahan</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Kuota Baru</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Catatan</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ([
                            ['tgl' => '22 Agu 2026', 'oleh' => 'SuperAdmin Raliva', 'tambah' => '+50', 'baru' => '200', 'cat' => 'Persetujuan request #RQ-0041', 'st' => 'Disetujui'],
                            ['tgl' => '20 Agu 2026', 'oleh' => 'Bima Prasetya (Owner)', 'tambah' => '+50', 'baru' => '—', 'cat' => 'Ajukan koleksi baru', 'st' => 'Menunggu'],
                            ['tgl' => '12 Jul 2026', 'oleh' => 'SuperAdmin Raliva', 'tambah' => '+30', 'baru' => '150', 'cat' => 'Awal pembukaan toko', 'st' => 'Disetujui'],
                            ['tgl' => '05 Jul 2026', 'oleh' => 'SuperAdmin Raliva', 'tambah' => '+120', 'baru' => '120', 'cat' => 'Inisialisasi toko', 'st' => 'Disetujui'],
                        ] as $row)
                            <tr class="border-b border-muted-border last:border-0">
                                <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">{{ $row['tgl'] }}</td>
                                <td class="py-3.5 px-4 text-on-surface whitespace-nowrap">{{ $row['oleh'] }}</td>
                                <td class="py-3.5 px-4 text-right font-bold text-gold-accent whitespace-nowrap">{{ $row['tambah'] }}</td>
                                <td class="py-3.5 px-4 text-right text-on-surface whitespace-nowrap">{{ $row['baru'] }}</td>
                                <td class="py-3.5 px-4 text-on-surface-variant max-w-[180px]">{{ $row['cat'] }}</td>
                                <td class="py-3.5 px-4 text-center">
                                    @if ($row['st'] === 'Disetujui')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Disetujui</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">Menunggu</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 border border-muted-border rounded-lg p-4 bg-surface-container-low flex items-start gap-3">
                <span class="material-symbols-outlined text-[20px] text-gold-accent mt-0.5">history</span>
                <p class="text-on-surface-variant font-body-md text-xs leading-relaxed">Semua penambahan tercatat permanen. Jika ditolak, SuperAdmin akan menyertakan alasan pada kolom catatan.</p>
            </div>
        </section>
    </div>
</div>
@endsection
