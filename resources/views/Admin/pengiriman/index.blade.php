@extends('layouts.admin')

@section('title', 'Pengiriman')

@section('header-title', 'Pengiriman')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Siapkan pengiriman, pilih kurir, dan masukkan nomor resi.')

@section('content')
<div class="space-y-section-gap">
    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Siap Kirim</h2>
        <div class="space-y-gutter">
            <div class="border border-muted-border rounded-lg p-5">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <p class="font-mono text-sm text-on-surface-variant">#RLV-2079 • Dewi Lestari</p>
                        <p class="font-title-md text-title-md text-on-surface mt-1">Relaxed Blazer + Straight Fit Pants</p>
                        <p class="font-body-md text-sm text-on-surface-variant mt-1">Tujuan: Bandung • Berat: 1,2 kg</p>
                    </div>
                    <form class="flex flex-col sm:flex-row gap-3" data-toast-message="Pesanan #RLV-2079 berhasil dikirim.">
                        <select required class="bg-transparent border border-muted-border rounded-lg px-4 py-3 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent">
                            <option value="">Pilih Kurir</option>
                            <option>JNE Reguler</option>
                            <option>SiCepat YES</option>
                            <option>AntareAja</option>
                        </select>
                        <input required class="bg-transparent border border-muted-border rounded-lg px-4 py-3 font-body-md text-sm focus:outline-none focus:border-gold-accent" type="text" placeholder="Masukkan No. Resi" />
                        <button type="submit" class="px-6 py-3 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-tertiary-container transition-colors btn-premium whitespace-nowrap">Kirim</button>
                    </form>
                </div>
            </div>

            <div class="border border-muted-border rounded-lg p-5">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <p class="font-mono text-sm text-on-surface-variant">#RLV-2077 • Maya Rossi</p>
                        <p class="font-title-md text-title-md text-on-surface mt-1">Oversized Linen Shirt</p>
                        <p class="font-body-md text-sm text-on-surface-variant mt-1">Tujuan: Surabaya • Berat: 0,6 kg</p>
                    </div>
                    <form class="flex flex-col sm:flex-row gap-3" data-toast-message="Pesanan #RLV-2077 berhasil dikirim.">
                        <select required class="bg-transparent border border-muted-border rounded-lg px-4 py-3 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent">
                            <option value="">Pilih Kurir</option>
                            <option>JNE Reguler</option>
                            <option>SiCepat YES</option>
                            <option>AntareAja</option>
                        </select>
                        <input required class="bg-transparent border border-muted-border rounded-lg px-4 py-3 font-body-md text-sm focus:outline-none focus:border-gold-accent" type="text" placeholder="Masukkan No. Resi" />
                        <button type="submit" class="px-6 py-3 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-tertiary-container transition-colors btn-premium whitespace-nowrap">Kirim</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="space-y-gutter">
        <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Dalam Pengiriman</h2>
        <div class="overflow-x-auto bg-surface-container-lowest border border-muted-border rounded-lg card-premium">
            <table class="w-full min-w-[750px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">ID Pesanan</th>
                        <th class="p-4 text-left">Kurir</th>
                        <th class="p-4 text-left">No. Resi</th>
                        <th class="p-4 text-left">Dikirim</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-mono text-on-surface">#RLV-2075</td>
                        <td class="p-4 text-on-surface">JNE Reguler</td>
                        <td class="p-4 font-mono text-on-surface">JNE2608210041</td>
                        <td class="p-4 text-on-surface-variant">Hari ini, 08.30</td>
                    </tr>
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-mono text-on-surface">#RLV-2071</td>
                        <td class="p-4 text-on-surface">SiCepat YES</td>
                        <td class="p-4 font-mono text-on-surface">SC2608200233</td>
                        <td class="p-4 text-on-surface-variant">Kemarin, 15.10</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
