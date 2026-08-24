@extends('layouts.owner')

@section('title', 'Komplain')

@section('header-title', 'Komplain')
@section('header-badge', '3 Terbuka')
@section('header-subtitle', 'Pantau dan bantu tangani komplain customer toko Anda.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        @for ($i = 0; $i < 4; $i++)
            <div class="h-28 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="space-y-gutter">
        @for ($i = 0; $i < 3; $i++)
            <div class="h-36 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
</div>

<div data-real class="hidden space-y-section-gap">
    {{-- Ringkasan --}}
    <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Komplain Terbuka</span>
            <span class="raliva-figure text-[26px] text-error">3</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">support_agent</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Menunggu Respons Toko</span>
            <span class="raliva-figure text-[26px] text-gold-accent">1</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">schedule</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Selesai Bulan Ini</span>
            <span class="raliva-figure text-[26px] text-secondary">9</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">task_alt</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Resolution Rate</span>
            <span class="raliva-figure text-[26px] text-on-surface"><span>96</span>%</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">thumb_up</span>
        </div>
    </section>

    {{-- Info Prioritas --}}
    <section data-reveal class="bg-error/5 border border-error/25 rounded-lg px-6 py-4 flex items-start gap-3">
        <span class="material-symbols-outlined text-[22px] text-error mt-0.5 shrink-0">priority_high</span>
        <p class="font-body-md text-sm text-on-surface"><span class="font-bold">Respons wajib &le; 24 jam.</span> Komplain dengan prioritas tinggi yang tidak segera direspons dapat memengaruhi skor kualitas layanan toko.</p>
    </section>

    {{-- Daftar Komplain --}}
    <section data-table-scope>
        <div data-reveal class="flex items-center justify-between gap-4 mb-6">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading whitespace-nowrap">Daftar Komplain</h2>
            <select data-table-filter="status-komplain" class="raliva-select">
                <option value="">Semua Status</option>
                <option value="baru">Komplain Baru</option>
                <option value="proses">Dalam Penanganan</option>
                <option value="selesai">Selesai</option>
            </select>
        </div>

        <div data-reveal-group class="space-y-gutter">
            @foreach ([
                ['kode' => 'CMP-0041', 'pesanan' => '#RLV-2088', 'customer' => 'Raka Aditya', 'subjek' => 'Pesanan dibatalkan tapi dana belum kembali', 'kategori' => 'Refund', 'tgl' => '22 Agu, 10:12', 'prioritas' => 'Tinggi', 'key' => 'baru'],
                ['kode' => 'CMP-0040', 'pesanan' => '#RLV-2079', 'customer' => 'Hendra Wijaya', 'subjek' => 'Paket diklaim terkirim namun tidak diterima', 'kategori' => 'Pengiriman', 'tgl' => '21 Agu, 16:45', 'prioritas' => 'Tinggi', 'key' => 'proses'],
                ['kode' => 'CMP-0038', 'pesanan' => '#RLV-2074', 'customer' => 'Salsa Nabila', 'subjek' => 'Kemasan penyok saat sampai', 'kategori' => 'Kondisi Barang', 'tgl' => '19 Agu, 09:30', 'prioritas' => 'Sedang', 'key' => 'selesai'],
                ['kode' => 'CMP-0036', 'pesanan' => '#RLV-2069', 'customer' => 'Bagus Permana', 'subjek' => 'Salah ukuran, minta tukar produk', 'kategori' => 'Produk', 'tgl' => '17 Agu, 13:20', 'prioritas' => 'Rendah', 'key' => 'selesai'],
                ['kode' => 'CMP-0034', 'pesanan' => '#RLV-2091', 'customer' => 'Aulia Rahma', 'subjek' => 'Tinta monogram sedikit pudar di sudut scarf', 'kategori' => 'Kualitas Produk', 'tgl' => '15 Agu, 08:05', 'prioritas' => 'Rendah', 'key' => 'selesai'],
            ] as $c)
                @php
                    $prioClass = $c['prioritas'] === 'Tinggi'
                        ? 'bg-error/10 text-error border-error/20'
                        : ($c['prioritas'] === 'Sedang' ? 'bg-gold-accent/10 text-gold-accent border-gold-accent/30' : 'bg-surface-container-high text-on-surface-variant border-outline-variant');
                @endphp
                <article data-reveal data-komplain-row data-status="{{ $c['key'] }}" class="bg-surface-container-lowest border {{ $c['key'] === 'baru' ? 'border-error/25' : 'border-muted-border' }} rounded-lg p-5 md:p-6 card-premium">
                    <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
                        <div class="flex items-start gap-4 min-w-0">
                            <div class="w-11 h-11 rounded-xl {{ $c['key'] === 'selesai' ? 'bg-secondary-container/20 text-secondary' : 'bg-error/10 text-error' }} border {{ $c['key'] === 'selesai' ? 'border-secondary/20' : 'border-error/25' }} flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined fill">{{ $c['key'] === 'selesai' ? 'check_circle' : 'report_problem' }}</span>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="font-bold text-on-surface">{{ $c['kode'] }}</p>
                                    <span class="text-xs text-on-surface-variant">• {{ $c['pesanan'] }} • {{ $c['kategori'] }}</span>
                                </div>
                                <p class="font-body-md text-sm text-on-surface mt-1.5 leading-snug">{{ $c['subjek'] }}</p>
                                <p class="text-xs text-on-surface-variant mt-1">{{ $c['customer'] }} • {{ $c['tgl'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-gutter shrink-0 self-start">
                            <span class="inline-flex items-center px-2 py-1 rounded-full {{ $prioClass }} text-[9px] font-bold uppercase border">Prioritas {{ $c['prioritas'] }}</span>
                            @if ($c['key'] === 'selesai')
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[9px] font-bold uppercase border border-secondary/20">Selesai</span>
                            @elseif ($c['key'] === 'proses')
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[9px] font-bold uppercase border border-gold-accent/30">Ditangani</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[9px] font-bold uppercase border border-error/20 animate-pulse">Baru</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-gutter mt-5 pt-4 border-t border-muted-border">
                        <button type="button" data-drawer-open="drawer-komplain" onclick="showRalivaToast('Percakapan komplain dibuka (demo).', 'chat')" class="py-2 px-4 bg-deep-onyx text-on-primary rounded-lg text-xs font-semibold btn-premium">Buka Percakapan</button>
                        <button type="button" onclick="showRalivaToast('Detail pesanan dibuka (demo).', 'receipt_long')" class="py-2 px-4 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Lihat Pesanan</button>
                    </div>
                </article>
            @endforeach
        </div>

        <div data-empty-state class="hidden flex-col items-center py-12 text-center gap-3">
            <span class="material-symbols-outlined text-[40px] text-on-surface-variant">inbox</span>
            <p class="text-on-surface-variant font-body-md text-sm">Tidak ada komplain pada status ini.</p>
        </div>
    </section>
</div>

{{-- Drawer Percakapan Komplain --}}
<div id="drawer-overlay" class="fixed inset-0 bg-black/50 z-[70] hidden opacity-0 transition-opacity duration-300"></div>
<div id="drawer-komplain" data-drawer-panel class="fixed inset-y-0 right-0 z-[80] w-full max-w-lg bg-surface-container-lowest border-l border-muted-border shadow-xl translate-x-full transition-transform duration-300 ease-in-out flex flex-col">
    <div class="flex items-start justify-between px-6 py-5 border-b border-muted-border shrink-0">
        <div>
            <p class="text-xs font-medium text-on-surface-variant">Komplain CMP-0040 • #RLV-2079</p>
            <h3 class="font-title-md text-title-md text-on-surface mt-1">Paket diklaim terkirim namun tidak diterima</h3>
        </div>
        <button type="button" data-drawer-close class="text-on-surface-variant hover:text-on-surface transition-colors">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>
    <div class="flex-1 overflow-y-auto p-6 space-y-4">
        {{-- Balasan Customer --}}
        <div class="flex gap-3">
            <div class="w-9 h-9 rounded-full bg-surface-container-high border border-outline-variant flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[18px] text-on-surface-variant">person</span>
            </div>
            <div class="bg-surface-container-low border border-muted-border rounded-lg rounded-tl-none px-4 py-3 max-w-[85%]">
                <p class="text-[11px] font-bold text-on-surface">Hendra Wijaya <span class="font-normal text-on-surface-variant">• 21 Agu, 16:45</span></p>
                <p class="font-body-md text-sm text-on-surface mt-1.5 leading-relaxed">Status pengiriman tertanda terkirim kemarin sore, tapi saya tidak menerima paket apa pun. Mohon dicek resi SCP-90211.</p>
            </div>
        </div>
        {{-- Balasan Owner --}}
        <div class="flex gap-3 flex-row-reverse">
            <div class="w-9 h-9 rounded-full bg-deep-onyx text-on-primary flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[18px]">storefront</span>
            </div>
            <div class="bg-gold-accent/10 border border-gold-accent/25 rounded-lg rounded-tr-none px-4 py-3 max-w-[85%]">
                <p class="text-[11px] font-bold text-gold-accent">Raliva Atelier Jakarta <span class="font-normal text-on-surface-variant dark:text-inverse-on-surface/60">• 21 Agu, 17:30</span></p>
                <p class="font-body-md text-sm text-on-surface mt-1.5 leading-relaxed">Terima kasih sudah mengabari, Pak Hendra. Kami sedang menghubungi kurir untuk investigasi titik serah terima dan akan memberikan update dalam 24 jam.</p>
            </div>
        </div>
        {{-- Sistem --}}
        <div class="flex justify-center">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-surface-container-high text-on-surface-variant text-[11px] font-bold uppercase border border-outline-variant">
                <span class="material-symbols-outlined text-[14px]">gavel</span>Eskalasi ke Super Admin — 21 Agu, 18:00
            </span>
        </div>
        {{-- Balasan Moderator --}}
        <div class="flex gap-3">
            <div class="w-9 h-9 rounded-full bg-surface-container-highest border border-outline-variant flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[18px] text-on-surface-variant">gavel</span>
            </div>
            <div class="bg-surface-container-low border border-muted-border rounded-lg rounded-tl-none px-4 py-3 max-w-[85%]">
                <p class="text-[11px] font-bold text-on-surface-variant">Moderator Raliva <span class="font-normal">• 21 Agu, 18:00</span></p>
                <p class="font-body-md text-sm text-on-surface mt-1.5 leading-relaxed">Investigasi kurir menunjukkan paket diserahkan ke locker lantai 3. Mohon koordinasi dengan pihak gedung. Jika tidak ditemukan dalam 48 jam, refund akan diproses penuh.</p>
            </div>
        </div>
    </div>
    <form data-toast-message="Balasan berhasil dikirim ke percakapan." class="shrink-0 border-t border-muted-border p-4 flex items-end gap-gutter">
        <textarea rows="2" placeholder="Tulis balasan atau update penyelesaian..." required class="raliva-textarea flex-1 resize-none"></textarea>
        <button type="submit" class="w-11 h-11 shrink-0 rounded-lg bg-deep-onyx text-on-primary flex items-center justify-center btn-premium" aria-label="Kirim">
            <span class="material-symbols-outlined">send</span>
        </button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('form[data-toast-message]').forEach((form) => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            window.showRalivaToast(form.getAttribute('data-toast-message'));
            form.reset();
        });
    });
</script>
@endpush
