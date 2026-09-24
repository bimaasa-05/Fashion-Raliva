@php $showAksi = $showAksi ?? false; @endphp
<div class="overflow-x-auto hidden md:block">
    <table class="w-full min-w-[900px] premium-table">
        <thead>
            <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                <th class="px-4 py-4 text-center w-12 text-[10px] font-semibold tracking-widest">No.</th>
                <th class="px-4 py-4 text-left text-[10px] font-semibold tracking-widest">Posisi</th>
                <th class="px-4 py-4 text-left text-[10px] font-semibold tracking-widest">Produk</th>
                <th class="px-4 py-4 text-left text-[10px] font-semibold tracking-widest">Toko</th>
                <th class="px-4 py-4 text-right text-[10px] font-semibold tracking-widest">Bayaran (Bid)</th>
                <th class="px-4 py-4 text-center text-[10px] font-semibold tracking-widest">Periode Aktif</th>
                <th class="px-4 py-4 text-center text-[10px] font-semibold tracking-widest">Status</th>
                @if($showAksi)
                    <th class="px-4 py-4 text-right text-[10px] font-semibold tracking-widest">Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody class="font-body-md text-sm">
            @forelse($slots as $slot)
                @php
                    $statusMap = [
                        'aktif' => ['Aktif', \App\Support\StatusStyle::badgeClass('aktif')],
                        'terjadwal' => ['Menunggu Aktif', \App\Support\StatusStyle::badgeClass('terjadwal')],
                        'nonaktif' => ['Nonaktif', \App\Support\StatusStyle::badgeClass('nonaktif')],
                        'ditunda' => ['Ditunda', \App\Support\StatusStyle::badgeClass('ditunda')],
                    ];
                    $st = $statusMap[$slot->status] ?? [$slot->status, \App\Support\StatusStyle::CLASS_NEUTRAL];
                    $rank = $loop->iteration;
                    $posCls = match(true) {
                        $rank === 1 => 'bg-gradient-to-br from-amber-300 via-amber-400 to-amber-500 text-white',
                        $rank === 2 => 'bg-gradient-to-br from-slate-400 via-slate-500 to-slate-700 text-white',
                        $rank === 3 => 'bg-amber-600 text-white',
                        default     => 'bg-surface-container-high border border-outline-variant text-on-surface',
                    };
                @endphp
                <tr data-table-row class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                    <td class="p-4 text-center text-on-surface-variant font-mono">{{ $loop->iteration }}</td>
                    <td class="p-4">
                        <span class="inline-flex w-8 h-8 rounded-full {{ $posCls }} items-center justify-center font-bold{{ $rank > 3 ? ' text-sm' : '' }}">{{ $rank }}</span>
                    </td>
                    <td class="p-4 font-medium text-on-surface">{{ $slot->product->nama_produk ?? '-' }}</td>
                    <td class="p-4 text-on-surface-variant">{{ $slot->store->nama_toko ?? '-' }}</td>
                    <td class="p-4 text-right font-title-md text-sm {{ $rank === 1 ? 'text-gold-accent' : 'text-on-surface' }} font-bold">Rp {{ number_format((float)$slot->nominal_bid, 0, ',', '.') }}</td>
                    <td class="p-4 text-center text-on-surface-variant whitespace-nowrap">{{ $slot->tanggal_mulai ? \Carbon\Carbon::parse($slot->tanggal_mulai)->locale('id')->translatedFormat('d M') : '-' }} – {{ $slot->tanggal_selesai ? \Carbon\Carbon::parse($slot->tanggal_selesai)->locale('id')->translatedFormat('d M Y') : 'Menunggu' }}</td>
                    <td class="p-4 text-center"><span class="inline-flex items-center gap-1 px-2 py-1 rounded-full {{ $st[1] }} text-[10px] font-bold uppercase border">{{ $st[0] }}</span>@if($slot->status === 'ditunda')<div class="mt-1"><span class="inline-flex px-2 py-0.5 rounded-full text-[9px] font-bold uppercase border {{ $slot->payment_status === 'terverifikasi' ? 'bg-success/10 text-success border-success/20' : ($slot->payment_status === 'ditolak' ? 'bg-error/10 text-error border-error/20' : 'bg-gold-accent/10 text-gold-accent border-gold-accent/20') }}">{{ $slot->payment_status ?? 'menunggu_verifikasi' }}</span></div>@if($slot->bankAccount)<div class="text-[10px] text-on-surface-variant mt-1">{{ $slot->bankAccount->bank->nama_bank ?? '' }} • {{ $slot->bankAccount->nomor_rekening }}</div>@endif @if($slot->file_bukti)<div class="mt-1"><a href="{{ asset('storage/' . $slot->file_bukti) }}" target="_blank" class="text-[10px] text-gold-accent hover:underline">Lihat Bukti</a></div>@endif @endif</td>
                    @if($showAksi)
                        <td class="p-4 text-right">
                            @if($slot->status === 'ditunda')
                                <div class="flex items-center justify-end gap-1 flex-wrap">
                                    <form action="{{ route('superadmin.peringkat-iklan.setujui', $slot) }}" method="POST" onsubmit="return openConfirmPeringkat(event, 'Setujui iklan ini? Biaya Rp {{ number_format((float) $slot->nominal_bid, 0, ',', '.') }} akan didebit dari wallet toko.', 'setujui')" class="inline-block">
                                        @csrf
                                        <button type="submit" class="px-2 py-1.5 bg-deep-onyx text-on-primary rounded-lg text-[10px] font-bold uppercase hover:bg-black">Setujui</button>
                                    </form>
                                    <button type="button" onclick="openTolakIklan({{ $slot->ad_slot_id }})" class="px-2 py-1.5 border border-error/30 rounded-lg text-[10px] font-bold uppercase text-error hover:bg-error/10">Tolak</button>
                                </div>
                            @else
                                <form action="{{ route('superadmin.peringkat-iklan.hapus', $slot) }}" method="POST" onsubmit="return openConfirmPeringkat(event, 'Hapus slot iklan ini?', 'hapus')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 border border-error/30 rounded-lg text-[11px] font-label-sm uppercase tracking-wider text-error hover:bg-error/10 transition-colors">Hapus</button>
                                </form>
                            @endif
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $showAksi ? 8 : 7 }}" class="p-8 text-center text-on-surface-variant">Belum ada slot iklan terdaftar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Mobile: kartu peringkat -->
<div class="md:hidden grid grid-cols-1 gap-gutter px-4 pb-4">
    @forelse($slots as $slot)
        @php
            $statusMap = [
                'aktif' => ['Aktif', \App\Support\StatusStyle::badgeClass('aktif')],
                'terjadwal' => ['Menunggu Aktif', \App\Support\StatusStyle::badgeClass('terjadwal')],
                'nonaktif' => ['Nonaktif', \App\Support\StatusStyle::badgeClass('nonaktif')],
                'ditunda' => ['Ditunda', \App\Support\StatusStyle::badgeClass('ditunda')],
            ];
            $st = $statusMap[$slot->status] ?? [$slot->status, \App\Support\StatusStyle::CLASS_NEUTRAL];
            $rank = $loop->iteration;
            $posCls = match(true) {
                $rank === 1 => 'bg-gradient-to-br from-amber-300 via-amber-400 to-amber-500 text-white',
                $rank === 2 => 'bg-gradient-to-br from-slate-400 via-slate-500 to-slate-700 text-white',
                $rank === 3 => 'bg-amber-600 text-white',
                default     => 'bg-surface-container-high border border-outline-variant text-on-surface',
            };
        @endphp
        <article class="bg-surface-container-lowest border border-muted-border rounded-xl p-4 card-premium relative overflow-hidden">
            <span class="material-symbols-outlined absolute right-1 bottom-1 text-[64px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">leaderboard</span>
            <div class="flex items-center justify-between gap-3 mb-3">
                <div class="flex items-center gap-3">
                    <span class="inline-flex w-9 h-9 rounded-full {{ $posCls }} items-center justify-center font-bold shrink-0">{{ $rank }}</span>
                    <div class="min-w-0">
                        <p class="font-title-md text-title-md text-on-surface truncate">{{ $slot->product->nama_produk ?? '-' }}</p>
                        <p class="text-on-surface-variant text-xs truncate">{{ $slot->store->nama_toko ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <dl class="space-y-2 font-body-md text-sm mb-4">
                <div class="flex justify-between gap-3">
                    <dt class="text-on-surface-variant">Bayaran (Bid)</dt>
                    <dd class="font-bold {{ $rank === 1 ? 'text-gold-accent' : 'text-on-surface' }} text-right">Rp {{ number_format((float)$slot->nominal_bid, 0, ',', '.') }}</dd>
                </div>
                <div class="flex justify-between gap-3">
                    <dt class="text-on-surface-variant">Periode</dt>
                    <dd class="text-on-surface-variant text-xs text-right whitespace-nowrap">{{ $slot->tanggal_mulai ? \Carbon\Carbon::parse($slot->tanggal_mulai)->locale('id')->translatedFormat('d M') : '-' }} – {{ $slot->tanggal_selesai ? \Carbon\Carbon::parse($slot->tanggal_selesai)->locale('id')->translatedFormat('d M Y') : 'Menunggu' }}</dd>
                </div>
                <div class="flex justify-between gap-3 items-center">
                    <dt class="text-on-surface-variant">Status</dt>
                    <dd class="text-right"><span class="inline-flex items-center gap-1 px-2 py-1 rounded-full {{ $st[1] }} text-[10px] font-bold uppercase border">{{ $st[0] }}</span></dd>
                </div>
                @if($slot->status === 'ditunda')
                    <div class="flex justify-between gap-3">
                        <dt class="text-on-surface-variant">Pembayaran</dt>
                        <dd class="text-right"><span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold uppercase border {{ $slot->payment_status === 'terverifikasi' ? 'bg-success/10 text-success border-success/20' : ($slot->payment_status === 'ditolak' ? 'bg-error/10 text-error border-error/20' : 'bg-gold-accent/10 text-gold-accent border-gold-accent/20') }}">{{ $slot->payment_status ?? 'menunggu_verifikasi' }}</span></dd>
                    </div>
                    @if($slot->bankAccount)
                        <div class="flex justify-between gap-3">
                            <dt class="text-on-surface-variant">Rekening</dt>
                            <dd class="text-xs text-right">{{ $slot->bankAccount->bank->nama_bank ?? '' }} {{ $slot->bankAccount->nomor_rekening }}</dd>
                        </div>
                    @endif
                    @if($slot->file_bukti)
                        <div class="flex justify-between gap-3">
                            <dt class="text-on-surface-variant">Bukti</dt>
                            <dd class="text-right"><a href="{{ asset('storage/' . $slot->file_bukti) }}" target="_blank" class="text-xs text-gold-accent hover:underline">Lihat Bukti</a></dd>
                        </div>
                    @endif
                @endif
            </dl>
            @if($showAksi)
                @if($slot->status === 'ditunda')
                <div class="grid grid-cols-2 gap-2">
                    <form action="{{ route('superadmin.peringkat-iklan.setujui', $slot) }}" method="POST" onsubmit="return openConfirmPeringkat(event, 'Setujui iklan ini? Biaya Rp {{ number_format((float) $slot->nominal_bid, 0, ',', '.') }} akan didebit dari wallet toko.', 'setujui')" class="inline-block">
                        @csrf
                        <button type="submit" class="w-full min-h-11 inline-flex items-center justify-center gap-2 bg-deep-onyx text-on-primary rounded-lg text-[11px] font-bold uppercase">Setujui</button>
                    </form>
                    <button type="button" onclick="openTolakIklan({{ $slot->ad_slot_id }})" class="w-full min-h-11 inline-flex items-center justify-center gap-2 border border-error/30 rounded-lg text-[11px] font-bold uppercase text-error hover:bg-error/10">Tolak</button>
                </div>
            @else
                <form action="{{ route('superadmin.peringkat-iklan.hapus', $slot) }}" method="POST" onsubmit="return openConfirmPeringkat(event, 'Hapus slot iklan ini?', 'hapus')">
                @csrf @method('DELETE')
                <button type="submit" class="w-full min-h-11 inline-flex items-center justify-center gap-2 border border-error/30 rounded-lg text-[11px] font-label-sm uppercase tracking-wider text-error hover:bg-error/10 transition-colors">
                    <span class="material-symbols-outlined text-[16px]">delete</span>Hapus
                </button>
            </form>
            @endif
            @endif
        </article>
    @empty
        <p class="text-center text-on-surface-variant py-10">Belum ada slot iklan terdaftar.</p>
    @endforelse
</div>
@if ($slots->hasPages())
    <div class="mt-6 flex justify-center">{{ $slots->links() }}</div>
@endif