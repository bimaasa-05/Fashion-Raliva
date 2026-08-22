@extends('layouts.admin')

@section('title', 'Komplain')

@section('header-title', 'Komplain')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Terima, balas, proses, dan eskalasi komplain customer.')

@section('content')
<div class="space-y-section-gap">
    <section>
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Komplain Perlu Respons</h2>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-gutter">
            <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="font-mono text-sm text-on-surface-variant">KOM-0312 â€¢ Pesanan #RLV-2068</p>
                        <p class="font-title-md text-title-md text-on-surface mt-1">Ukuran tidak sesuai</p>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Terbuka</span>
                </div>
                <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 mb-4">
                    <p class="font-body-md text-sm text-on-surface-variant">"Saya order size L tapi yang datang M. Mohon solusinya, karena butuh untuk acara minggu depan." â€” <span class="text-on-surface font-bold">Sarah Jenkins</span></p>
                </div>
                <textarea class="w-full border border-muted-border bg-surface-container-low rounded-lg p-3 font-body-md text-sm focus:outline-none focus:border-gold-accent mb-4" rows="2" placeholder="Tulis balasan untuk customer..."></textarea>
                <div class="flex gap-3">
                    <button type="button" onclick="showRalivaToast('Balasan terkirim ke customer.', 'send')" class="flex-1 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-tertiary-container transition-colors btn-premium">Kirim Balasan</button>
                    <button class="flex-1 py-2.5 border border-muted-border text-on-surface font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-surface-container-low transition-colors" onclick="showRalivaToast('Komplain dieskalasi ke Owner Toko.', 'move_up')">Eskalasi ke Owner</button>
                </div>
            </div>

            <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="font-mono text-sm text-on-surface-variant">KOM-0311 â€¢ Pesanan #RLV-2063</p>
                        <p class="font-title-md text-title-md text-on-surface mt-1">Pengiriman terlambat</p>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Diproses</span>
                </div>
                <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 mb-4">
                    <p class="font-body-md text-sm text-on-surface-variant">"Sudah 7 hari belum sampai, estimasi awalnya 3 hari." â€” <span class="text-on-surface font-bold">Andi Pratama</span></p>
                    <p class="font-body-md text-sm text-on-surface mt-3 pt-3 border-t border-muted-border"><span class="font-bold">Balasan Admin:</span> "Mohon maaf atas keterlambatannya. Kami sudah menghubungi kurir, paket dalam proses penyelidikan."</p>
                </div>
                <div class="flex gap-3">
                    <button class="flex-1 py-2.5 border border-muted-border text-on-surface font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-surface-container-low transition-colors" onclick="showRalivaToast('Riwayat percakapan komplain (demo).', 'forum')">Lihat Percakapan</button>
                </div>
            </div>
        </div>
    </section>

    <section class="space-y-gutter">
        <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Riwayat Komplain</h2>
        <div class="overflow-x-auto bg-surface-container-lowest border border-muted-border rounded-lg card-premium">
            <table class="w-full min-w-[750px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">ID Komplain</th>
                        <th class="p-4 text-left">Customer</th>
                        <th class="p-4 text-left">Topik</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-left">Selesai</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-mono text-on-surface">KOM-0305</td>
                        <td class="p-4 text-on-surface">Dewi Lestari</td>
                        <td class="p-4 text-on-surface">Barang rusak</td>
                        <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Selesai</span></td>
                        <td class="p-4 text-on-surface-variant">18 Agu 2026</td>
                    </tr>
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-mono text-on-surface">KOM-0299</td>
                        <td class="p-4 text-on-surface">Budi Santoso</td>
                        <td class="p-4 text-on-surface">Salah kirim warna</td>
                        <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Selesai</span></td>
                        <td class="p-4 text-on-surface-variant">12 Agu 2026</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
