@extends('layouts.admin')

@section('title', 'Data Pesanan')

@section('header-title', 'Data Pesanan')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Lihat detail dan proses pesanan sesuai alur status.')

@section('content')
<section data-table-scope class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Daftar Pesanan Toko</h2>
        <button type="button" data-modal-open="modal-buat-pesanan" class="flex items-center justify-center gap-2 px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0 self-start sm:self-auto">
            <span class="material-symbols-outlined text-[18px]">add_shopping_cart</span> Buat Pesanan
        </button>
    </div>

    <div class="mb-6 bg-surface-container-low border border-muted-border rounded-lg p-4 flex flex-col lg:flex-row lg:items-center gap-3">
        <div class="flex items-center gap-2 shrink-0">
            <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
            <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter Status</span>
        </div>
        <div class="hidden lg:block w-px h-6 bg-muted-border"></div>
        <div data-chip-group data-chip-key="status" class="flex flex-wrap gap-2">
            <button type="button" data-chip="semua" class="px-4 py-2 rounded-lg bg-deep-onyx text-on-primary border border-deep-onyx font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Semua</button>
            <button type="button" data-chip="baru" class="px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Baru</button>
            <button type="button" data-chip="diproses" class="px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Diproses</button>
            <button type="button" data-chip="dikirim" class="px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Dikirim</button>
            <button type="button" data-chip="selesai" class="px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Selesai</button>
            <button type="button" data-chip="dibatalkan" class="px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Dibatalkan</button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[900px] premium-table">
            <thead>
                <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                    <th class="p-4 text-left">ID Pesanan</th>
                    <th class="p-4 text-left">Pelanggan</th>
                    <th class="p-4 text-left">Produk</th>
                    <th class="p-4 text-right">Total</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="font-body-md text-sm">
                <tr data-table-row data-status="baru" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                    <td class="p-4 font-mono text-on-surface">#RLV-2081</td>
                    <td class="p-4">
                        <p class="text-on-surface">Sarah Jenkins</p>
                        <p class="text-on-surface-variant text-xs">sarah@email.com</p>
                    </td>
                    <td class="p-4 text-on-surface">3 produk</td>
                    <td class="p-4 text-right font-bold text-gold-accent">Rp 890.000</td>
                    <td class="p-4 text-center">
                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Baru</span>
                    </td>
                    <td class="p-4 text-right whitespace-nowrap">
                        <button type="button" onclick="showRalivaToast('Pesanan #RLV-2081 diproses.', 'task_alt')" class="px-3 py-1.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:bg-tertiary-container transition-colors">Proses</button>
                        <button type="button" data-detail-open="detail-pesanan" data-d-nomor="#RLV-2081" data-d-pelanggan="Sarah Jenkins (sarah@email.com)" data-d-produk="3 produk" data-d-total="Rp 890.000" data-d-status="Baru" class="px-3 py-1.5 ml-1 border border-muted-border text-on-surface font-label-sm text-[10px] uppercase rounded hover:bg-surface-container-low transition-colors">Detail</button>
                    </td>
                </tr>
                <tr data-table-row data-status="diproses" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                    <td class="p-4 font-mono text-on-surface">#RLV-2079</td>
                    <td class="p-4">
                        <p class="text-on-surface">Dewi Lestari</p>
                        <p class="text-on-surface-variant text-xs">dewi.l@email.com</p>
                    </td>
                    <td class="p-4 text-on-surface">2 produk</td>
                    <td class="p-4 text-right font-bold text-gold-accent">Rp 1.150.000</td>
                    <td class="p-4 text-center">
                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">Diproses</span>
                    </td>
                    <td class="p-4 text-right whitespace-nowrap">
                        <button type="button" onclick="showRalivaToast('Pesanan #RLV-2079 dikirim ke gudang.', 'local_shipping')" class="px-3 py-1.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:bg-tertiary-container transition-colors">Kirim ke Gudang</button>
                        <button type="button" data-detail-open="detail-pesanan" data-d-nomor="#RLV-2079" data-d-pelanggan="Dewi Lestari (dewi.l@email.com)" data-d-produk="2 produk" data-d-total="Rp 1.150.000" data-d-status="Diproses" class="px-3 py-1.5 ml-1 border border-muted-border text-on-surface font-label-sm text-[10px] uppercase rounded hover:bg-surface-container-low transition-colors">Detail</button>
                    </td>
                </tr>
                <tr data-table-row data-status="dikirim" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                    <td class="p-4 font-mono text-on-surface">#RLV-2075</td>
                    <td class="p-4">
                        <p class="text-on-surface">Budi Santoso</p>
                        <p class="text-on-surface-variant text-xs">budi.s@email.com</p>
                    </td>
                    <td class="p-4 text-on-surface">1 produk</td>
                    <td class="p-4 text-right font-bold text-gold-accent">Rp 455.000</td>
                    <td class="p-4 text-center">
                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">Dikirim</span>
                    </td>
                    <td class="p-4 text-right whitespace-nowrap">
                        <button type="button" data-detail-open="detail-pesanan" data-d-nomor="#RLV-2075" data-d-pelanggan="Budi Santoso (budi.s@email.com)" data-d-produk="1 produk" data-d-total="Rp 455.000" data-d-status="Dikirim" class="px-3 py-1.5 border border-muted-border text-on-surface font-label-sm text-[10px] uppercase rounded hover:bg-surface-container-low transition-colors">Detail</button>
                    </td>
                </tr>
                <tr data-table-row data-status="dibatalkan" class="hover:bg-surface-container-low transition-colors">
                    <td class="p-4 font-mono text-on-surface">#RLV-2070</td>
                    <td class="p-4">
                        <p class="text-on-surface">Maya Rossi</p>
                        <p class="text-on-surface-variant text-xs">maya.r@email.com</p>
                    </td>
                    <td class="p-4 text-on-surface">4 produk</td>
                    <td class="p-4 text-right font-bold text-gold-accent">Rp 2.340.000</td>
                    <td class="p-4 text-center">
                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Dibatalkan</span>
                    </td>
                    <td class="p-4 text-right whitespace-nowrap">
                        <button type="button" data-detail-open="detail-pesanan" data-d-nomor="#RLV-2070" data-d-pelanggan="Maya Rossi (maya.r@email.com)" data-d-produk="4 produk" data-d-total="Rp 2.340.000" data-d-status="Dibatalkan" class="px-3 py-1.5 border border-muted-border text-on-surface font-label-sm text-[10px] uppercase rounded hover:bg-surface-container-low transition-colors">Detail</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<div id="detail-pesanan" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-16 md:mt-24 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl p-6 max-h-[80vh] overflow-y-auto">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Detail Pesanan</h3>
                <p class="text-on-surface-variant font-label-sm text-xs uppercase tracking-wider mt-1"><span data-slot="nomor"></span></p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <dl class="space-y-4 font-body-md text-sm">
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Pelanggan</dt><dd class="text-on-surface text-right"><span data-slot="pelanggan"></span></dd></div>
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Jumlah Produk</dt><dd class="text-on-surface text-right"><span data-slot="produk"></span></dd></div>
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Total</dt><dd class="font-bold text-gold-accent text-right"><span data-slot="total"></span></dd></div>
            <div class="flex justify-between gap-4"><dt class="text-on-surface-variant shrink-0">Status</dt><dd class="text-on-surface text-right"><span data-slot="status"></span></dd></div>
        </dl>
        <button type="button" data-modal-close class="w-full mt-6 py-3 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Tutup</button>
    </div>
</div>

<!-- Modal Buat Pesanan -->
<div id="modal-buat-pesanan" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px]" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Buat Pesanan Manual</h3>
                <p class="text-on-surface-variant font-body-md text-sm mt-1">Untuk pesanan offline / walk-in di toko.</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form data-toast-message="Pesanan manual berhasil dibuat." class="p-6 space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="pesananPelanggan">Pelanggan</label>
                    <select id="pesananPelanggan" required class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent">
                        <option>Maya Rossi (maya.r@email.com)</option>
                        <option>Dewi Lestari (dewi.l@email.com)</option>
                        <option>Andi Pratama (andi.p@email.com)</option>
                        <option>Tamu / Walk-in</option>
                    </select>
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="pesananProduk">Produk</label>
                    <select id="pesananProduk" required class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent">
                        <option>Kemeja Linen Premium — Rp 289.000</option>
                        <option>Dress Midi Linen — Rp 329.000</option>
                        <option>Blazer Wool Relaxed — Rp 549.000</option>
                        <option>Silk Scarf — Rp 159.000</option>
                    </select>
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="pesananQty">Jumlah</label>
                    <input type="number" min="1" value="1" id="pesananQty" required class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="pesananKurir">Kurir</label>
                    <select id="pesananKurir" required class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent">
                        <option>JNE Reguler</option>
                        <option>SiCepat YES</option>
                        <option>POS Indonesia</option>
                        <option>Ambil Sendiri di Toko</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2">Metode Pembayaran</label>
                <div class="grid grid-cols-3 gap-3">
                    @foreach(['transfer' => 'Transfer', 'qris' => 'QRIS', 'cod' => 'COD'] as $val => $label)
                        <label class="flex items-center justify-center px-4 py-3 border border-muted-border rounded-lg text-on-surface-variant font-label-sm text-[11px] uppercase cursor-pointer hover:bg-surface-container-low hover:border-gold-accent hover:text-gold-accent transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10 has-[:checked]:text-gold-accent">
                            <input type="radio" class="sr-only" name="pesananBayar" value="{{ $val }}" {{ $val === 'qris' ? 'checked' : '' }} />
                            {{ $label }}
                        </label>
                    @endforeach
                </div>
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="pesananAlamat">Alamat Pengiriman</label>
                <textarea id="pesananAlamat" rows="2" placeholder="Nama penerima, alamat lengkap, no. HP..." class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors resize-none placeholder-on-surface-variant/50"></textarea>
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="pesananCatatan">Catatan</label>
                <textarea id="pesananCatatan" rows="2" placeholder="Opsional — misal permintaan khusus pelanggan" class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors resize-none placeholder-on-surface-variant/50"></textarea>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Buat Pesanan</button>
            </div>
        </form>
    </div>
</div>
@endsection
