<div class="relative" data-notification-container>
    <button type="button" data-notification-toggle class="relative text-on-surface hover:text-secondary transition-colors" aria-label="Notifikasi">
        <span class="material-symbols-outlined">notifications</span>
        <span class="absolute top-0 right-0 w-2 h-2 bg-error rounded-full"></span>
    </button>
    <div data-notification-menu class="hidden absolute right-0 top-full mt-2 w-80 max-w-[calc(100vw-2rem)] bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl z-[60] overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 border-b border-muted-border">
            <p class="font-label-sm text-sm text-on-surface uppercase tracking-wider">Notifikasi</p>
            <button type="button" class="font-label-sm text-[10px] text-gold-accent uppercase hover:underline" onclick="alert('Semua notifikasi ditandai sudah dibaca.')">Tandai Dibaca</button>
        </div>
        <ul class="max-h-80 overflow-y-auto">
            <li class="flex gap-3 px-4 py-3 border-b border-muted-border hover:bg-surface-container-low transition-colors cursor-pointer">
                <span class="material-symbols-outlined text-[20px] text-gold-accent mt-0.5">payments</span>
                <div>
                    <p class="font-body-md text-sm text-on-surface">Bukti pembayaran pesanan <span class="font-bold">#RLV-2081</span> menunggu verifikasi.</p>
                    <p class="font-label-sm text-[10px] text-on-surface-variant uppercase mt-1">5 menit lalu</p>
                </div>
            </li>
            <li class="flex gap-3 px-4 py-3 border-b border-muted-border hover:bg-surface-container-low transition-colors cursor-pointer">
                <span class="material-symbols-outlined text-[20px] text-gold-accent mt-0.5">shopping_bag</span>
                <div>
                    <p class="font-body-md text-sm text-on-surface">Pesanan baru dari <span class="font-bold">Sarah Jenkins</span> perlu diproses.</p>
                    <p class="font-label-sm text-[10px] text-on-surface-variant uppercase mt-1">20 menit lalu</p>
                </div>
            </li>
            <li class="flex gap-3 px-4 py-3 border-b border-muted-border hover:bg-surface-container-low transition-colors cursor-pointer">
                <span class="material-symbols-outlined text-[20px] text-gold-accent mt-0.5">support_agent</span>
                <div>
                    <p class="font-body-md text-sm text-on-surface">Komplain baru: <span class="font-bold">Ukuran tidak sesuai</span> dari Andi Pratama.</p>
                    <p class="font-label-sm text-[10px] text-on-surface-variant uppercase mt-1">1 jam lalu</p>
                </div>
            </li>
            <li class="flex gap-3 px-4 py-3 hover:bg-surface-container-low transition-colors cursor-pointer">
                <span class="material-symbols-outlined text-[20px] text-gold-accent mt-0.5">local_shipping</span>
                <div>
                    <p class="font-body-md text-sm text-on-surface">Tugas pengiriman: <span class="font-bold">3 paket</span> menunggu input resi.</p>
                    <p class="font-label-sm text-[10px] text-on-surface-variant uppercase mt-1">2 jam lalu</p>
                </div>
            </li>
        </ul>
        <a href="#" class="block text-center px-4 py-3 font-label-sm text-label-sm text-gold-accent uppercase tracking-widest hover:bg-surface-container-low transition-colors border-t border-muted-border">Lihat Semua Notifikasi</a>
    </div>
</div>
