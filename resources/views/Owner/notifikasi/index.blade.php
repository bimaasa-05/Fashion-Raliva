@extends('layouts.owner')

@section('title', 'Notifikasi')

@section('header-title', 'Notifikasi')
@section('header-subtitle', 'Semua pemberitahuan penting untuk toko Anda.')

@section('content')
<div data-skeleton class="space-y-gutter">
    @for ($i = 0; $i < 5; $i++)
        <div class="h-24 bg-surface-container-high rounded-lg animate-pulse"></div>
    @endfor
</div>

<div data-real class="hidden space-y-section-gap">
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg px-6 py-4 flex items-center justify-between gap-4 card-premium">
        <div class="flex items-center gap-3">
            <span class="relative flex w-2.5 h-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-gold-accent opacity-60"></span>
                <span class="relative inline-flex rounded-full w-2.5 h-2.5 bg-gold-accent"></span>
            </span>
            <p class="font-title-md text-sm text-on-surface"><span>7</span> notifikasi belum dibaca</p>
        </div>
        <button type="button" onclick="showRalivaToast('Semua notifikasi ditandai sudah dibaca.', 'mark_email_read')" class="text-xs font-semibold text-gold-accent hover:underline shrink-0">Tandai Semua Dibaca</button>
    </section>

    {{-- Hari Ini --}}
    <section>
        <h2 data-reveal class="text-xs font-medium text-on-surface-variant mb-gutter px-1">Hari Ini</h2>
        <div data-reveal-group class="space-y-gutter">
            @foreach ([
                ['shopping_bag', 'Pesanan baru #RLV-2093 dari Sarah Jenkins menunggu konfirmasi.', '14:32', true],
                ['verified', 'Produk Blazer Wool Premium telah disetujui moderator dan tayang.', '13:05', true],
                ['payments', 'Pencairan dana WD-0092 sedang diproses ke BCA ****8821.', '11:40', false],
                ['star', 'Ulasan baru 5 bintang dari Sarah Jenkins untuk Trench Coat Signature.', '09:15', false],
                ['warning', 'Stok Silk Scarf Monogram habis — segera ajukan produksi ulang.', '08:02', false],
                ['support_agent', 'Komplain CMP-0041 berprioritas Tinggi memerlukan respons Anda.', '07:48', true],
            ] as $n)
                <article data-reveal class="bg-surface-container-lowest border {{ $n[3] ? 'border-l-[3px] border-l-gold-accent border-muted-border' : 'border-muted-border' }} rounded-lg px-5 py-4 flex items-start gap-4 card-premium hover:border-gold-accent/40 transition-colors">
                    <div class="w-10 h-10 rounded-xl bg-surface-container-high flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[20px] text-gold-accent">{{ $n[0] }}</span>
                    </div>
                    <p class="flex-1 font-body-md text-sm text-on-surface leading-relaxed">{{ $n[1] }}</p>
                    <span class="font-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant whitespace-nowrap mt-1">{{ $n[2] }}</span>
                </article>
            @endforeach
        </div>
    </section>

    {{-- Sebelumnya --}}
    <section>
        <h2 data-reveal class="text-xs font-medium text-on-surface-variant mb-gutter px-1">Sebelumnya</h2>
        <div data-reveal-group class="space-y-gutter">
            @foreach ([
                ['local_shipping', 'Pengiriman #RLV-2091 telah tiba di tujuan dan dikonfirmasi customer.', 'Kemarin, 19:22'],
                ['grid_view', 'Slot Paket Growth Anda terpakai 142/200. Sisa 58 slot tersedia.', 'Kemarin, 12:00'],
                ['account_balance_wallet', 'Saldo tertunda Rp 3.420.000 dari pesanan #RLV-2087 telah tersedia.', '20 Agu, 17:30'],
                ['assignment_return', 'Kasus refund RFD-0076 telah diselesaikan penuh oleh Super Admin.', '18 Agu, 14:05'],
                ['precision_manufacturing', 'Permintaan produksi PRQ-0039 selesai — 60 unit masuk gudang utama.', '16 Agu, 16:45'],
                ['groups', 'Sinta Maharani bergabung sebagai staf Produksi di Raliva Store Bandung.', '14 Agu, 09:30'],
            ] as $n)
                <article data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg px-5 py-4 flex items-start gap-4 card-premium hover:border-gold-accent/40 transition-colors">
                    <div class="w-10 h-10 rounded-xl bg-surface-container-high flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[20px] text-on-surface-variant">{{ $n[0] }}</span>
                    </div>
                    <p class="flex-1 font-body-md text-sm text-on-surface-variant leading-relaxed">{{ $n[1] }}</p>
                    <span class="font-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant whitespace-nowrap mt-1">{{ $n[2] }}</span>
                </article>
            @endforeach
        </div>
    </section>

    <div data-reveal class="flex justify-center pt-2">
        <button type="button" onclick="showRalivaToast('Memuat notifikasi lama (demo).', 'history')" class="px-8 py-3 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Muat Lebih Banyak</button>
    </div>
</div>
@endsection
