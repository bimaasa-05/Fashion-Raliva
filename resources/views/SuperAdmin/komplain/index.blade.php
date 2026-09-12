@extends('layouts.superadmin')

@section('title', 'Komplain')

@section('header-title', 'Komplain')
@section('header-badge', 'Pantau')

@section('header-subtitle', 'Monitor dan tangani komplain Customer terhadap toko.')

@php
    $badgeMap = [
        'open' => ['label' => 'Terbuka', 'class' => 'bg-gold-accent/10 text-gold-accent border-gold-accent/20'],
        'menunggu' => ['label' => 'Terbuka', 'class' => 'bg-gold-accent/10 text-gold-accent border-gold-accent/20'],
        'baru' => ['label' => 'Baru', 'class' => 'bg-gold-accent/10 text-gold-accent border-gold-accent/20'],
        'diproses' => ['label' => 'Diproses', 'class' => 'bg-surface-container-high text-on-surface border-outline-variant'],
        'selesai' => ['label' => 'Selesai', 'class' => 'bg-secondary-container/20 text-secondary border-secondary/20'],
        'ditutup' => ['label' => 'Ditutup', 'class' => 'bg-error/10 text-error border-error/20'],
        'escalated' => ['label' => 'Eskalasi', 'class' => 'bg-gold-accent/10 text-gold-accent border-gold-accent/30'],
    ];
@endphp

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    <section>
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Ringkasan Komplain</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-gutter">
            <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium flex flex-col gap-2 relative overflow-hidden">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Terbuka</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-gold-accent">{{ $stats['open'] }}</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">perlu ditangani</span>
            </div>
            <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium flex flex-col gap-2 relative overflow-hidden">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Diproses</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">{{ $stats['diproses'] }}</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">sedang di follow-up</span>
            </div>
            <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium flex flex-col gap-2 relative overflow-hidden">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Selesai</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">{{ $stats['selesai'] }}</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">ditutup oleh sistem</span>
            </div>
            <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium flex flex-col gap-2 relative overflow-hidden">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Ditutup</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">support_agent</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">{{ $stats['ditutup'] }}</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">ditutup manual</span>
            </div>
        </div>
    </section>

    <section data-table-scope class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex items-center justify-between gap-3 mb-6 flex-wrap">
            <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Daftar Komplain</h2>
            <button type="button" data-filter-toggle class="md:hidden inline-flex items-center justify-center gap-2 px-4 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">
                <span class="material-symbols-outlined text-[18px]">tune</span>
                Filter
                <span class="material-symbols-outlined text-[18px] transition-transform duration-300" data-filter-chevron>expand_more</span>
            </button>
        </div>

        <!-- Filters -->
        <div data-filter-panel class="hidden md:block mb-6">
            <div class="mb-4 bg-surface-container-low border border-muted-border rounded-lg p-4 flex flex-col lg:flex-row lg:items-center gap-3">
                <div class="flex items-center gap-2 shrink-0">
                    <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
                    <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter Status</span>
                </div>
                <div class="hidden lg:block w-px h-6 bg-muted-border"></div>
                <div id="chip-group" class="flex flex-wrap gap-2">
                    <button type="button" data-chip="semua" class="chip-btn px-4 py-2 rounded-lg bg-deep-onyx border border-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Semua ({{ $stats['semua'] }})</button>
                    <button type="button" data-chip="open" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Terbuka ({{ $stats['open'] }})</button>
                    <button type="button" data-chip="diproses" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Diproses ({{ $stats['diproses'] }})</button>
                    <button type="button" data-chip="selesai" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Selesai ({{ $stats['selesai'] }})</button>
                    <button type="button" data-chip="ditutup" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Ditutup ({{ $stats['ditutup'] }})</button>
                </div>
            </div>

            <!-- Search + Result Count -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                    <input id="komplain-search" class="w-full bg-surface-container-low border border-muted-border rounded-lg pl-11 pr-10 py-3 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" type="text" placeholder="Cari ID komplain, nama pelanggan, atau toko..." />
                    <button type="button" id="clear-search" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-gold-accent opacity-0 transition-opacity">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>
                <p class="text-on-surface-variant font-body-md text-xs shrink-0">
                    <span id="result-count">{{ $complaints->total() }}</span> komplain
                </p>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto hidden md:block">
            <table class="w-full min-w-[900px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant text-sm uppercase">
                        <th class="p-6 w-12 text-center">No.</th>
                        <th class="p-6">Detail Komplain</th>
                        <th class="p-6">Toko</th>
                        <th class="p-6">Status</th>
                        <th class="p-6">Kategori</th>
                        <th class="p-6">Kirim</th>
                        <th class="p-6">Aksi</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse ($complaints as $c)
                        @php
                            $badge = $badgeMap[$c->status] ?? ['label' => '-', 'class' => ''];
                            $kode = 'KL-' . str_pad((string) $c->complaint_id, 10, '0', STR_PAD_LEFT);
                        @endphp
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors group"
                            data-table-row data-status="{{ $c->status }}" data-search="{{ strtolower($kode.' '.($c->user->nama_lengkap ?? '').' '.($c->store->nama_toko ?? '').' '.($c->store->owner->nama_lengkap ?? '')) }}"
                            data-id="{{ $c->complaint_id }}" data-kode="{{ $kode }}">
                            <td class="p-6 text-center text-on-surface-variant font-mono row-num"></td>
                            <td class="p-6">
                                <p class="font-title-md text-title-md text-on-surface">{{ $c->subjek }}</p>
                                <p class="font-mono text-xs text-on-surface-variant">{{ $kode }}</p>
                            </td>
                            <td class="p-6">
                                <p class="text-on-surface">{{ $c->store->nama_toko }}</p>
                                <p class="text-on-surface-variant text-xs">{{ $c->store->owner->nama_lengkap ?? '-' }}</p>
                            </td>
                            <td class="p-6">
                                <span class="inline-flex items-center px-2 py-1 rounded {{ $badge['class'] }} text-xs uppercase">{{ $badge['label'] }}</span>
                            </td>
                            <td class="p-6 text-xs">{{ $c->kategori }}</td>
                            <td class="p-6 text-xs text-on-surface-variant">
                                {{ $c->dibuat_pada ? \Carbon\Carbon::parse($c->dibuat_pada)->locale('id')->diffForHumans() : '-' }}
                            </td>
                            <td class="p-6">
                                <div class="flex items-center gap-2 justify-end">
                                    <button type="button" onclick="openChatModal({{ $c->complaint_id }}, '{{ $kode }}', '{{ addslashes($c->subjek ?? $c->kategori) }}', {{ $c->eskalasi_oleh_sa ? 'true' : 'false' }})"
                                        class="flex items-center gap-1 px-3 py-1.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:opacity-80 transition-opacity btn-premium">
                                        <span class="material-symbols-outlined text-sm">chat</span>
                                        Buka
                                    </button>

                                    @if (in_array($c->status, [\App\Models\Complaint::STATUS_OPEN, \App\Models\Complaint::STATUS_DIPROSES, \App\Models\Complaint::STATUS_ESKALASI, 'baru'], true))
                                        @if (in_array($c->status, [\App\Models\Complaint::STATUS_OPEN, \App\Models\Complaint::STATUS_DIPROSES], true) && ! $c->eskalasi_oleh_sa)
                                            <form method="POST" action="{{ route('superadmin.komplain.eskalasi', $c->complaint_id) }}" onsubmit="return openConfirmKomplain(event, 'eskalasi', '{{ $kode }}')" class="inline-block">
                                                @csrf
                                                <button type="submit" title="Eskalasi"
                                                    class="w-8 h-8 flex items-center justify-center bg-gold-accent/10 text-gold-accent border border-gold-accent/25 hover:bg-gold-accent hover:text-white transition-colors">
                                                    <span class="material-symbols-outlined text-sm">emergency</span>
                                                </button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('superadmin.komplain.tutup', $c->complaint_id) }}" onsubmit="return openConfirmKomplain(event, 'tutup', '{{ $kode }}')" class="inline-block">
                                            @csrf
                                            <button type="submit" title="Tutup Komplain"
                                                class="w-8 h-8 flex items-center justify-center border border-outline text-on-surface hover:bg-surface-container-high transition-colors">
                                                <span class="material-symbols-outlined text-sm">check_circle</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-12 text-center text-on-surface-variant">Tidak ada komplain tercatat.</td></tr>
                    @endforelse
                    <tr id="empty-search" class="hidden">
                        <td colspan="7" class="p-8 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <span class="material-symbols-outlined text-on-surface-variant/50 text-[32px]">search_off</span>
                                <p class="text-on-surface-variant font-body-md text-sm">Tidak ada komplain yang cocok.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Mobile: kartu per komplain -->
        <div class="md:hidden grid grid-cols-1 gap-gutter">
            @forelse ($complaints as $c)
                @php
                    $badge = $badgeMap[$c->status] ?? ['label' => '-', 'class' => ''];
                    $kode = 'KL-' . str_pad((string) $c->complaint_id, 10, '0', STR_PAD_LEFT);
                @endphp
                <article data-table-row data-status="{{ $c->status }}" data-search="{{ strtolower($kode.' '.($c->user->nama_lengkap ?? '').' '.($c->store->nama_toko ?? '').' '.($c->store->owner->nama_lengkap ?? '')) }}" class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="min-w-0">
                            <p class="font-title-md text-title-md text-on-surface leading-tight">{{ $c->subjek }}</p>
                            <p class="font-mono text-xs text-on-surface-variant mt-0.5">{{ $kode }}</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded {{ $badge['class'] }} text-[10px] font-bold uppercase shrink-0">{{ $badge['label'] }}</span>
                    </div>

                    <dl class="space-y-2 font-body-md text-sm mb-4">
                        <div class="flex justify-between gap-3">
                            <dt class="text-on-surface-variant">Toko</dt>
                            <dd class="text-on-surface text-right">{{ $c->store->nama_toko }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-on-surface-variant">Pemilik</dt>
                            <dd class="text-on-surface text-right">{{ $c->store->owner->nama_lengkap ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-on-surface-variant">Kategori</dt>
                            <dd class="text-on-surface text-right">{{ $c->kategori }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-on-surface-variant">Kirim</dt>
                            <dd class="text-on-surface text-right">{{ $c->dibuat_pada ? \Carbon\Carbon::parse($c->dibuat_pada)->locale('id')->diffForHumans() : '-' }}</dd>
                        </div>
                    </dl>

                    <div class="flex gap-gutter">
                        <button type="button" onclick="openChatModal({{ $c->complaint_id }}, '{{ $kode }}', '{{ addslashes($c->subjek ?? $c->kategori) }}', {{ $c->eskalasi_oleh_sa ? 'true' : 'false' }})" class="flex-1 min-h-11 inline-flex items-center justify-center gap-2 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:opacity-80 transition-opacity btn-premium">
                            <span class="material-symbols-outlined text-[16px]">chat</span>Buka
                        </button>

                        @if (in_array($c->status, [\App\Models\Complaint::STATUS_OPEN, \App\Models\Complaint::STATUS_DIPROSES, \App\Models\Complaint::STATUS_ESKALASI, 'baru'], true))
                            @if (in_array($c->status, [\App\Models\Complaint::STATUS_OPEN, \App\Models\Complaint::STATUS_DIPROSES], true) && ! $c->eskalasi_oleh_sa)
                                <form method="POST" action="{{ route('superadmin.komplain.eskalasi', $c->complaint_id) }}" onsubmit="return openConfirmKomplain(event, 'eskalasi', '{{ $kode }}')" class="shrink-0">
                                    @csrf
                                    <button type="submit" title="Eskalasi" class="min-h-11 w-11 flex items-center justify-center bg-gold-accent/10 text-gold-accent border border-gold-accent/25 hover:bg-gold-accent hover:text-white transition-colors rounded-lg">
                                        <span class="material-symbols-outlined text-[18px]">emergency</span>
                                    </button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('superadmin.komplain.tutup', $c->complaint_id) }}" onsubmit="return openConfirmKomplain(event, 'tutup', '{{ $kode }}')" class="shrink-0">
                                @csrf
                                <button type="submit" title="Tutup Komplain" class="min-h-11 w-11 flex items-center justify-center border border-outline text-on-surface hover:bg-surface-container-high transition-colors rounded-lg">
                                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </article>
            @empty
                <p class="text-center text-on-surface-variant py-10">Tidak ada komplain tercatat.</p>
            @endforelse
            <p id="empty-search-mobile" class="hidden text-center text-on-surface-variant py-10">Tidak ada komplain yang cocok.</p>
        </div>
        @if ($complaints->hasPages())
            <div class="mt-6 flex justify-center">{{ $complaints->links() }}</div>
        @endif
    </section>
</div>

<!-- Chat Komplain Modal -->
<div class="hidden fixed inset-0 z-[55] bg-black/60 backdrop-blur-sm"
     id="chat-container" onclick="if(event.target===this) closeChatModal()">

    <div class="min-h-full lg:h-full flex flex-col justify-end lg:flex-row lg:justify-end" onclick="if(event.target===this) closeChatModal()">

    <div id="chat-panel" class="flex flex-col w-full md:w-[520px] lg:w-[560px] xl:w-[600px] md:max-w-[88vw] lg:max-w-full bg-surface-container-low border-t md:border lg:border-t-0 lg:border-l border-muted-border rounded-t-3xl md:rounded-2xl lg:rounded-none max-h-[85dvh] md:max-h-[78dvh] lg:max-h-full lg:h-full overflow-hidden md:shadow-2xl lg:shadow-none" onclick="event.stopPropagation()">
        <div class="relative flex items-center justify-between gap-2 lg:gap-3 pl-6 pr-3 lg:px-6 py-3.5 lg:py-4 border-b border-muted-border shrink-0 bg-surface-container-low z-10 overflow-visible" id="chat-header">
            <div class="min-w-0 flex-1" id="chat-header-title">
                <h3 class="font-title-md text-title-md text-on-surface truncate leading-tight" id="chat-subject">-</h3>
                <p class="font-mono text-on-surface-variant text-xs mt-0.5 truncate" id="chat-kode">-</p>
            </div>
            <div class="flex items-center gap-2 lg:gap-3 shrink-0" id="chat-header-actions">
                <span id="chat-status" class="hidden inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide border bg-surface-container-high text-on-surface-variant border-outline-variant shrink-0 whitespace-nowrap"></span>
                <button type="button" onclick="toggleChatSearch()" id="chat-search-toggle" class="w-11 h-11 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors cursor-pointer shrink-0" title="Cari pesan" aria-label="Cari pesan">
                    <span class="material-symbols-outlined text-[20px]">search</span>
                </button>
                <div class="relative shrink-0" id="chat-more-wrap">
                    <button type="button" onclick="toggleChatMoreMenu()" id="chat-more-btn" class="w-11 h-11 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors cursor-pointer shrink-0" title="Menu" aria-label="Menu">
                        <span class="material-symbols-outlined text-[20px]">more_vert</span>
                    </button>
                    <div id="chat-more-menu" class="hidden absolute right-0 top-full mt-2 min-w-[220px] rounded-xl border border-outline-variant bg-surface-container-high shadow-xl z-40 py-1.5">
                        <button type="button" onclick="openWallpaperPicker()" class="w-full text-left px-4 py-2.5 font-body-md text-sm text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer flex items-center gap-2">
                            <span class="material-symbols-outlined text-[19px]">wallpaper</span>Ganti Wallpaper
                        </button>
                        <button type="button" onclick="resetWallpaper()" class="w-full text-left px-4 py-2.5 font-body-md text-sm text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors cursor-pointer flex items-center gap-2">
                            <span class="material-symbols-outlined text-[19px]">restart_alt</span>Reset Wallpaper
                        </button>
                    </div>
                </div>
                <button type="button" onclick="closeChatModal()" class="w-11 h-11 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors shrink-0" title="Tutup" aria-label="Tutup">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            <div id="chat-search-panel" class="absolute inset-0 hidden items-center gap-2 lg:gap-3 px-6 bg-surface-container-low">
                <button type="button" onclick="toggleChatSearch()" class="w-11 h-11 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors cursor-pointer shrink-0" title="Tutup pencarian" aria-label="Tutup pencarian">
                    <span class="material-symbols-outlined text-[20px]">search</span>
                </button>
                <input id="chat-search-input" type="text" inputmode="search" autocomplete="off" placeholder="Cari pesan..." class="flex-1 min-w-0 bg-transparent font-body-sm text-body-sm text-on-surface placeholder:text-on-surface-variant/70 border-b border-[var(--border-soft)] focus:border-secondary py-2 outline-none"/>
                <button type="button" onclick="clearChatSearch()" class="hidden w-11 h-11 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors cursor-pointer shrink-0" id="chat-search-clear" title="Hapus pencarian" aria-label="Hapus pencarian">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
                <span id="chat-search-count" class="font-label-sm text-label-sm text-on-surface-variant shrink-0 hidden"></span>
            </div>
        </div>

        <div class="relative flex-1 flex flex-col min-h-0 overflow-hidden" id="chat-content">
            <div class="flex-1 overflow-y-auto px-4 lg:px-6 py-4 lg:py-6 space-y-3 min-h-0 bg-transparent" id="chat-messages" style="overscroll-behavior: contain;">
                <div class="flex justify-center items-center py-8">
                    <div class="w-10 h-10 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
                </div>
            </div>

            <div id="chat-input-area" class="relative px-3 lg:px-4 pt-3 pb-[max(0.75rem,env(safe-area-inset-bottom))] pl-[max(0.75rem,env(safe-area-inset-left))] pr-[max(0.75rem,env(safe-area-inset-right))] hidden shrink-0 w-full bg-transparent">
                <div id="chat-emoji-panel" class="hidden absolute bottom-full mb-3 left-3 lg:left-4 z-10 w-[264px] max-w-[calc(100vw-4rem)] lg:w-[320px] max-h-[220px] overflow-y-auto rounded-xl border border-outline-variant bg-surface-container-high p-3 shadow-xl"></div>
                <div id="chat-composer" class="flex items-end gap-1 lg:gap-1.5 bg-surface-container-lowest border border-[rgba(0,0,0,0.06)] rounded-[26px] lg:rounded-[28px] px-2 lg:px-2.5 py-2 lg:py-2.5 shadow-sm transition-colors duration-150 focus-within:border-secondary w-full">
                    <button type="button" onclick="toggleEmojiPanel()" id="chat-emoji-toggle" aria-label="Emoji" title="Emoji" class="w-11 h-11 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors cursor-pointer shrink-0">
                        <span class="material-symbols-outlined text-[20px]">mood</span>
                    </button>
                    <textarea id="chat-input" rows="1" maxlength="2000" placeholder="Tulis pesan..."
                        class="flex-1 min-w-0 bg-transparent border-0 outline-none resize-none px-1 py-2.5 font-body-md text-sm text-on-surface placeholder-on-surface-variant/70"
                        onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();sendMessage();}" aria-label="Tulis pesan"></textarea>
                    <button type="button" onclick="sendMessage()" id="chat-send" aria-label="Kirim pesan" title="Kirim"
                        class="w-11 h-11 flex items-center justify-center bg-secondary text-white shrink-0 hover:opacity-80 active:scale-[0.96] transition-all disabled:opacity-40 rounded-full">
                        <span class="material-symbols-outlined text-[20px]">send</span>
                    </button>
                </div>
                <p id="chat-closed-note" class="hidden text-center font-body-sm text-body-sm text-on-surface-variant pt-4">Komplain telah selesai dan tidak dapat dibalas lagi.</p>
            </div>
        </div>
    </div>
    <input type="file" id="chat-wallpaper-input" accept="image/*" class="hidden">
    </div>
    <div id="chat-delete-dialog" class="hidden fixed inset-0 z-[70] flex items-end sm:items-center justify-center bg-black/50" onclick="if(event.target===this){event.stopPropagation();closeDeleteDialog();}">
        <div class="w-full sm:max-w-sm bg-surface-container-low rounded-t-3xl sm:rounded-2xl p-2 sm:p-4 border border-outline-variant shadow-2xl" onclick="event.stopPropagation()">
            <p class="font-title-sm text-title-sm text-on-surface px-4 pt-3 pb-2">Hapus pesan ini?</p>
            <button type="button" data-del-per="me" onclick="deleteMessage()" class="w-full text-left px-4 py-3 hover:bg-surface-container-high transition-colors cursor-pointer rounded-xl">
                <span class="block font-body-md text-sm text-on-surface">Hapus untuk diri sendiri</span>
                <span class="block font-body-md text-sm text-on-surface-variant/80">Pesan hanya dihapus dari perangkat Anda</span>
            </button>
            <button type="button" onclick="closeDeleteDialog()" class="w-full text-left px-4 py-3 mt-1 hover:bg-surface-container-high transition-colors cursor-pointer rounded-xl">
                <span class="font-body-md text-sm text-secondary">Batal</span>
            </button>
        </div>
    </div>
    <!-- Modal Konfirmasi Komplain (eskalasi/tutup) -->
    <div id="confirmKomplainModal" class="hidden fixed inset-0 z-[75] items-center justify-center p-4 bg-black/50 backdrop-blur-sm" onclick="if (event.target === this) closeConfirmKomplain()">
        <div class="bg-surface-container-lowest w-full max-w-md rounded-xl border border-muted-border shadow-2xl overflow-hidden">
            <div class="p-8">
                <div id="confirm-komplain-icon" class="w-14 h-14 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center mx-auto mb-5">
                    <span id="confirm-komplain-icon-sym" class="material-symbols-outlined text-gold-accent text-[28px]">help</span>
                </div>
                <h3 id="confirm-komplain-title" class="font-title-md text-title-md text-on-surface mb-2 text-center">Konfirmasi</h3>
                <p id="confirm-komplain-desc" class="text-on-surface-variant text-sm text-center mb-4">Lanjutkan aksi ini?</p>
                <div class="flex space-x-3">
                    <button type="button" class="flex-1 bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-surface-container-low transition-colors rounded-lg" onclick="closeConfirmKomplain()">Batal</button>
                    <button type="button" id="confirm-komplain-submit" class="flex-1 bg-deep-onyx text-on-primary font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-black transition-colors rounded-lg btn-premium">Ya, Lanjutkan</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

    @push('styles')
<style>
    #chat-content {
        background-image: url('/images/wallpaper-chat-white.png');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-color: #F8F6F2;
        position: relative;
        isolation: isolate;
    }
    html.theme-dark #chat-content, .dark #chat-content {
        background-image: url('/images/wallpaper-chat-black.png');
        background-color: #171717;
    }
    #chat-content[style*="background-image"] {
        background-size: cover !important;
        background-position: center !important;
        background-repeat: no-repeat !important;
    }
    #chat-content::before {
        content: '';
        position: absolute;
        inset: 0;
        background: rgba(248,246,242,0.55);
        pointer-events: none;
        z-index: 0;
    }
    html.theme-dark #chat-content::before, .dark #chat-content::before { background: rgba(0,0,0,.28); }
    #chat-content > * { position: relative; z-index: 1; }
    #chat-edit-wallpaper {
        background-image: url('/images/wallpaper-chat-white.png');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-color: #F8F6F2;
    }
    html.theme-dark #chat-edit-wallpaper, .dark #chat-edit-wallpaper {
        background-image: url('/images/wallpaper-chat-black.png');
        background-color: #171717;
    }
    #chat-messages { overscroll-behavior: contain; }
    @keyframes raliva-chat-backdrop-in { from { opacity: 0; } to { opacity: 1; } }
    @keyframes raliva-chat-backdrop-out { from { opacity: 1; } to { opacity: 0; } }
    @keyframes raliva-chat-sheet-in-mobile { from { transform: translateY(100%); } to { transform: translateY(0); } }
    @keyframes raliva-chat-sheet-out-mobile { from { transform: translateY(0); } to { transform: translateY(100%); } }
    @keyframes raliva-chat-sheet-in-desktop { from { transform: translateX(100%); } to { transform: translateX(0); } }
    @keyframes raliva-chat-sheet-out-desktop { from { transform: translateX(0); } to { transform: translateX(100%); } }
    .raliva-chat-in { animation: raliva-chat-backdrop-in .2s ease-out both; }
    .raliva-chat-out { animation: raliva-chat-backdrop-out .2s ease-in both; }
    .raliva-chat-in-sheet { animation: raliva-chat-sheet-in-mobile .28s cubic-bezier(.22,.68,.34,1) both; }
    .raliva-chat-out-sheet { animation: raliva-chat-sheet-out-mobile .28s cubic-bezier(.22,1,.36,1) both; }
    @media (min-width: 1024px) {
        .raliva-chat-in-sheet { animation-name: raliva-chat-sheet-in-desktop; }
        .raliva-chat-out-sheet { animation-name: raliva-chat-sheet-out-desktop; }
    }
    @media (prefers-reduced-motion: reduce) {
        .raliva-chat-in, .raliva-chat-out, .raliva-chat-in-sheet, .raliva-chat-out-sheet { animation: none; }
    }
</style>
@endpush

@push('scripts')
<script>
    let currentChat = { id: null, polling: null, closing: false };
    const myId = {{ Auth::id() }};

    // Wallpaper (sync with customer)
    function openWallpaperPicker() {
        closeChatMoreMenu();
        const input = document.getElementById('chat-wallpaper-input');
        if (input) input.click();
    }
    function applyWallpaper(url, persist) {
        const layer = document.getElementById('chat-content');
        if (!layer) return;
        if (url) {
            layer.style.backgroundImage = "url('" + url.replace(/'/g, "\\'") + "')";
            layer.style.backgroundSize = 'cover';
            layer.style.backgroundPosition = 'center';
        } else {
            layer.style.backgroundImage = '';
            layer.style.backgroundSize = '';
            layer.style.backgroundPosition = '';
        }
        if (persist) {
            try { localStorage.setItem('raliva_sa_wallpaper', url || ''); } catch (_) {}
        }
    }
    function resetWallpaper() {
        applyWallpaper('', true);
        closeChatMoreMenu();
    }
    function initWallpaper() {
        let saved = '';
        try { saved = localStorage.getItem('raliva_sa_wallpaper') || ''; } catch (_) {}
        if (saved) applyWallpaper(saved, false);
    }
    document.addEventListener('change', function (ev) {
        if (ev.target && ev.target.id !== 'chat-wallpaper-input') return;
        const file = ev.target.files && ev.target.files[0];
        ev.target.value = '';
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function () { applyWallpaper(reader.result, true); };
        reader.readAsDataURL(file);
    });
    document.addEventListener('DOMContentLoaded', initWallpaper);

    // Search & more menu (sync customer)
    function toggleChatSearch() {
        const panel = document.getElementById('chat-search-panel');
        const title = document.getElementById('chat-header-title');
        const actions = document.getElementById('chat-header-actions');
        if (!panel) return;
        const opening = panel.classList.contains('hidden');
        if (opening) {
            panel.classList.remove('hidden'); panel.classList.add('flex');
            if (title) title.classList.add('hidden');
            if (actions) actions.classList.add('hidden');
            document.getElementById('chat-search-input')?.focus();
        } else {
            panel.classList.add('hidden'); panel.classList.remove('flex');
            if (title) title.classList.remove('hidden');
            if (actions) actions.classList.remove('hidden');
            clearChatSearch();
        }
    }
    function clearChatSearch() {
        const input = document.getElementById('chat-search-input');
        const count = document.getElementById('chat-search-count');
        const clearBtn = document.getElementById('chat-search-clear');
        if (input) input.value = '';
        if (count) { count.textContent = ''; count.classList.add('hidden'); }
        if (clearBtn) clearBtn.classList.add('hidden');
        document.querySelectorAll('#chat-messages .chat-msg').forEach(el => el.style.display = '');
    }
    function toggleChatMoreMenu() {
        const menu = document.getElementById('chat-more-menu');
        if (!menu) return;
        menu.classList.toggle('hidden');
    }
    function closeChatMoreMenu() {
        document.getElementById('chat-more-menu')?.classList.add('hidden');
    }
    function toggleEmojiPanel() {
        const panel = document.getElementById('chat-emoji-panel');
        if (!panel) return;
        panel.classList.toggle('hidden');
        if (!panel.classList.contains('hidden') && !panel.dataset.loaded) {
            const emojis = ['😀','😂','😍','😭','😡','👍','🙏','🔥','❤️','✨','😊','🤔','😎','🥺','🫡','👏','🎉','💯'];
            panel.innerHTML = emojis.map(e => '<button type="button" onclick="insertEmoji(\''+e+'\')" class="w-9 h-9 flex items-center justify-center hover:bg-surface-container-low rounded-lg text-lg">'+e+'</button>').join('');
            panel.dataset.loaded = '1';
            panel.classList.add('grid','grid-cols-6','gap-1');
        }
    }
    function insertEmoji(e) {
        const input = document.getElementById('chat-input');
        if (!input) return;
        const start = input.selectionStart || input.value.length;
        const end = input.selectionEnd || start;
        input.value = input.value.slice(0, start) + e + input.value.slice(end);
        input.focus();
        input.selectionStart = input.selectionEnd = start + e.length;
    }
    document.getElementById('chat-search-input')?.addEventListener('input', function () {
        const q = this.value.trim().toLowerCase();
        const clearBtn = document.getElementById('chat-search-clear');
        const count = document.getElementById('chat-search-count');
        if (clearBtn) clearBtn.classList.toggle('hidden', !q);
        let shown = 0, total = 0;
        document.querySelectorAll('#chat-messages .chat-msg').forEach(el => {
            total++;
            const txt = (el.querySelector('[data-pesan]')?.textContent || '').toLowerCase();
            const ok = !q || txt.includes(q);
            el.style.display = ok ? '' : 'none';
            if (ok) shown++;
        });
        if (count) {
            if (q) { count.textContent = shown + ' / ' + total; count.classList.remove('hidden'); }
            else { count.textContent = ''; count.classList.add('hidden'); }
        }
    });
    document.addEventListener('click', function (ev) {
        if (!ev.target.closest('#chat-more-wrap')) closeChatMoreMenu();
        if (!ev.target.closest('#chat-emoji-panel') && !ev.target.closest('#chat-emoji-toggle')) document.getElementById('chat-emoji-panel')?.classList.add('hidden');
    });

    function openChatModal(id, kode, subject, isOpen) {
        currentChat.id = id;
        currentChat.closing = false;
        document.getElementById('chat-kode').textContent = kode;
        document.getElementById('chat-subject').textContent = subject;
        const inputArea = document.getElementById('chat-input-area');
        inputArea.classList.add('hidden');

        const container = document.getElementById('chat-container');
        const panel = document.getElementById('chat-panel');
        container.classList.remove('hidden', 'raliva-chat-out');
        panel.classList.remove('raliva-chat-out-sheet');
        void container.offsetWidth;
        container.classList.add('raliva-chat-in');
        panel.classList.add('raliva-chat-in-sheet');
        document.body.style.overflow = 'hidden';

        loadMessages();

        if (currentChat.polling) clearInterval(currentChat.polling);
        currentChat.polling = setInterval(loadMessages, 5000);
    }

    function closeChatModal() {
        const container = document.getElementById('chat-container');
        if (currentChat.closing || container.classList.contains('hidden')) return;
        currentChat.closing = true;
        document.body.style.overflow = '';
        if (currentChat.polling) clearInterval(currentChat.polling);
        currentChat.id = null;
        closeChatMenu();
        closeDeleteDialog();
        closeChatMoreMenu();
        document.getElementById('chat-emoji-panel')?.classList.add('hidden');
        const sp = document.getElementById('chat-search-panel');
        if (sp && !sp.classList.contains('hidden')) toggleChatSearch();

        const panel = document.getElementById('chat-panel');
        container.classList.remove('raliva-chat-in');
        panel.classList.remove('raliva-chat-in-sheet');
        void container.offsetWidth;
        container.classList.add('raliva-chat-out');
        panel.classList.add('raliva-chat-out-sheet');
        setTimeout(function () {
            if (!currentChat.closing) return;
            container.classList.add('hidden');
            container.classList.remove('raliva-chat-out');
            panel.classList.remove('raliva-chat-out-sheet');
            currentChat.closing = false;
        }, 320);
    }

    async function loadMessages() {
        if (!currentChat.id) return;
        try {
            const url = '{{ route('superadmin.komplain.messages', ':id:') }}'.replace(':id:', currentChat.id);
            const resp = await fetch(url, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!resp.ok) throw new Error('Gagal memuat pesan');
            const messages = await resp.json();
            renderMessages(messages);
        } catch (err) {
            showChatError(err.message);
        }
    }

    function showChatError(message) {
        const el = document.getElementById('chat-messages');
        el.innerHTML = `
            <div class="text-center py-8">
                <p class="text-on-surface-variant text-sm">${escapeHtml(message)}</p>
                <p class="text-xs text-on-surface-variant/60 mt-1">Coba muat ulang halaman.</p>
            </div>`;
        el.scrollTop = el.scrollHeight;
    }

    function fmtTs(value) {
        return new Date(value).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', hour12: false });
    }

    function chatMenuMarkup(id, btnColor) {
        return '<span class="relative shrink-0 chat-menu-wrap">' +
            '<button type="button" data-menu-btn="' + id + '" onclick="toggleChatMenu(' + id + ')" class="chat-menu-btn ' + btnColor + ' lg:opacity-0 lg:group-hover:opacity-100 lg:group-focus-within:opacity-100 focus:opacity-100 transition-opacity cursor-pointer rounded-full w-7 h-7 flex items-center justify-center" title="…"><span class="material-symbols-outlined text-[17px]">more_horiz</span></button>' +
            '<span data-menu="' + id + '" class="chat-menu hidden absolute right-0 top-full mt-1 min-w-[170px] z-30 rounded-xl border border-outline-variant bg-surface-container-high py-1 shadow-xl">';
    }

    function renderMessages(messages) {
        const el = document.getElementById('chat-messages');
        el.innerHTML = messages.map(m => {
            const mine = m.sender_id === myId;
            const align = mine ? 'justify-end' : 'justify-start';
            const time = mine ? 'text-white/40' : 'text-on-surface-variant/50';
            const bubble = mine ? 'bg-secondary text-white' : 'bg-surface-container-low';
            const meta = mine ? 'text-white/60' : 'text-on-surface-variant';
            const edited = m.edited_at ? ' <span class="italic">(diedit)</span>' : '';
            if (m.deleted) {
                const delBubble = mine ? 'bg-secondary text-white' : 'bg-surface-container-low';
                const delText = mine ? 'text-white' : 'text-error';
                const delBtn = mine ? 'text-white/50 hover:text-white' : 'text-on-surface-variant hover:text-on-surface';
                const menu = chatMenuMarkup(m.complaint_message_id, delBtn) +
                    '<button type="button" onclick="openDeleteDialog(' + m.complaint_message_id + ')" class="w-full text-left px-4 py-2.5 font-body-md text-sm text-error hover:bg-error/10 transition-colors cursor-pointer flex items-center gap-2"><span class="material-symbols-outlined text-[16px]">delete</span>' + escapeHtml('Hapus pesan') + '</button>' +
                    '</span></span>';
                return '<div class="flex ' + align + ' group chat-msg" data-mid="' + m.complaint_message_id + '">' +
                    '<div class="max-w-[82%] lg:max-w-[72%] rounded-2xl px-3.5 lg:px-4 pt-2.5 pb-5 relative ' + delBubble + ' shadow-sm" data-bubble>' +
                    '<div class="flex items-center justify-between gap-2">' +
                    '<p class="font-body-md text-sm italic flex items-center gap-1.5 ' + delText + '"><span class="material-symbols-outlined text-[16px] leading-none shrink-0">block</span>' + escapeHtml('Pesan ini telah dihapus') + '</p>' +
                    menu +
                    '</div>' +
                    '<span class="absolute bottom-1.5 right-2.5 text-[10px] leading-none ' + time + '">' + fmtTs(m.created_at) + '</span>' +
                    '</div></div>';
            }
            const menu = '<span class="relative shrink-0 chat-menu-wrap"><button type="button" data-menu-btn="' + m.complaint_message_id + '" onclick="toggleChatMenu(' + m.complaint_message_id + ')" class="chat-menu-btn ' + (mine ? 'text-white/60 hover:text-white' : 'text-on-surface-variant hover:text-on-surface') + ' lg:opacity-0 lg:group-hover:opacity-100 lg:group-focus-within:opacity-100 focus:opacity-100 transition-opacity cursor-pointer rounded-full w-7 h-7 flex items-center justify-center" title="…"><span class="material-symbols-outlined text-[17px]">more_horiz</span></button><span data-menu="' + m.complaint_message_id + '" class="chat-menu hidden absolute right-0 top-full mt-1 min-w-[170px] z-30 rounded-xl border border-outline-variant bg-surface-container-high py-1 shadow-xl"><button type="button" onclick="openDeleteDialog(' + m.complaint_message_id + ')" class="w-full text-left px-4 py-2.5 font-body-md text-sm text-error hover:bg-error/10 transition-colors cursor-pointer flex items-center gap-2"><span class="material-symbols-outlined text-[16px]">delete</span>' + escapeHtml('Hapus pesan') + '</button></span></span>';
            const sender = mine ? 'Super Admin' : (m.sender?.role === 'customer' ? m.sender?.nama_lengkap : 'Toko');
            return '<div class="flex ' + align + ' group chat-msg" data-mid="' + m.complaint_message_id + '">' +
                '<div class="max-w-[82%] lg:max-w-[72%] rounded-2xl px-3.5 lg:px-4 pt-3 pb-5 relative ' + bubble + ' shadow-sm" data-bubble>' +
                '<div class="flex items-start justify-between gap-2 mb-1">' +
                '<p class="text-[11px] ' + meta + ' uppercase tracking-[0.06em] font-medium">' + escapeHtml(sender) + '</p>' +
                menu +
                '</div>' +
                '<p class="font-body-md text-sm whitespace-pre-wrap break-words leading-relaxed" data-pesan>' + escapeHtml(m.pesan) + '</p>' +
                '<span class="absolute bottom-1.5 right-2.5 text-[10px] leading-none ' + time + '">' + fmtTs(m.created_at) + edited + '</span>' +
                '</div></div>';
        }).join('');
        el.scrollTop = el.scrollHeight;
        document.getElementById('chat-input-area').classList.remove('hidden');
    }

    let chatMenuId = null;
    let deleteDialogMsgId = null;

    function toggleChatMenu(id) {
        const menu = document.querySelector('[data-menu="' + id + '"]');
        if (!menu) return;
        const opening = menu.classList.contains('hidden');
        closeChatMenu();
        if (opening) {
            menu.classList.remove('hidden');
            chatMenuId = id;
        }
    }

    function closeChatMenu() {
        if (chatMenuId === null) return;
        const menu = document.querySelector('[data-menu="' + chatMenuId + '"]');
        if (menu) menu.classList.add('hidden');
        chatMenuId = null;
    }

    function openDeleteDialog(id) {
        closeChatMenu();
        deleteDialogMsgId = id;
        document.getElementById('chat-delete-dialog').classList.remove('hidden');
    }

    function closeDeleteDialog() {
        deleteDialogMsgId = null;
        document.getElementById('chat-delete-dialog').classList.add('hidden');
    }

    let _pendingKomplainForm = null;
    function openConfirmKomplain(e, aksi, kode) {
        e.preventDefault();
        _pendingKomplainForm = e.target;
        const isEskalasi = aksi === 'eskalasi';
        document.getElementById('confirm-komplain-title').textContent = isEskalasi ? 'Eskalasi Komplain?' : 'Tutup Komplain?';
        document.getElementById('confirm-komplain-desc').textContent = (isEskalasi ? 'Eskalasi ' : 'Tutup ') + kode + (isEskalasi ? ' ke Owner?' : ' (status akan menjadi selesai)?');
        const iconWrap = document.getElementById('confirm-komplain-icon');
        const iconSym = document.getElementById('confirm-komplain-icon-sym');
        const submitBtn = document.getElementById('confirm-komplain-submit');
        if (isEskalasi) {
            iconWrap.className = 'w-14 h-14 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center mx-auto mb-5';
            iconSym.className = 'material-symbols-outlined text-gold-accent text-[28px]';
            iconSym.textContent = 'emergency';
            submitBtn.className = 'flex-1 bg-gold-accent text-white font-label-sm text-label-sm py-3 uppercase tracking-widest hover:opacity-90 transition-opacity rounded-lg btn-premium';
            submitBtn.textContent = 'Ya, Eskalasi';
        } else {
            iconWrap.className = 'w-14 h-14 rounded-full bg-success/10 border border-success/25 flex items-center justify-center mx-auto mb-5';
            iconSym.className = 'material-symbols-outlined text-success text-[28px]';
            iconSym.textContent = 'check_circle';
            submitBtn.className = 'flex-1 bg-success text-white font-label-sm text-label-sm py-3 uppercase tracking-widest hover:opacity-90 transition-opacity rounded-lg btn-premium';
            submitBtn.textContent = 'Ya, Tutup';
        }
        const m = document.getElementById('confirmKomplainModal');
        m.classList.remove('hidden'); m.classList.add('flex');
        return false;
    }
    function closeConfirmKomplain() {
        const m = document.getElementById('confirmKomplainModal');
        if (m) { m.classList.add('hidden'); m.classList.remove('flex'); }
        _pendingKomplainForm = null;
    }
    document.getElementById('confirm-komplain-submit')?.addEventListener('click', () => {
        if (_pendingKomplainForm) _pendingKomplainForm.submit();
    });

    async function deleteMessage() {
        const id = deleteDialogMsgId;
        if (!id) return;
        const btnEl = document.querySelector('#chat-delete-dialog [data-del-per="me"]');
        if (btnEl) btnEl.disabled = true;
        try {
            const url = '{{ route('superadmin.komplain.messages.destroy', [':cid:', ':mid:']) }}'.replace(':cid:', currentChat.id).replace(':mid:', id);
            const resp = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ per: 'me' })
            });
            closeDeleteDialog();
            if (resp.ok) {
                loadMessages();
            } else {
                let msg = 'Gagal menghapus pesan';
                try {
                    const data = await resp.json();
                    if (data && data.message) msg = data.message;
                } catch (_) {}
                alert(msg);
            }
        } catch (_) {
            closeDeleteDialog();
        }
    }

    document.addEventListener('click', function (ev) {
        if (chatMenuId !== null && ev.target.closest) {
            const inMenu = ev.target.closest('[data-menu="' + chatMenuId + '"]') ||
                ev.target.closest('[data-menu-btn="' + chatMenuId + '"]') ||
                ev.target.closest('[data-mid="' + chatMenuId + '"]');
            if (!inMenu) closeChatMenu();
        }
    });

    function escapeHtml(text) {
        const d = document.createElement('div');
        d.textContent = text;
        return d.innerHTML;
    }

    async function sendMessage() {
        const input = document.getElementById('chat-input');
        const pesan = input.value.trim();
        if (!pesan || !currentChat.id) return;

        document.getElementById('chat-send').disabled = true;
        input.value = '';

        try {
            const url = '{{ route('superadmin.komplain.messages.store', ':id:') }}'.replace(':id:', currentChat.id);
            const resp = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ pesan })
            });
            if (resp.ok) loadMessages();
            else throw new Error('Gagal mengirim pesan');
        } catch (_) {
            input.value = pesan;
        } finally {
            document.getElementById('chat-send').disabled = false;
        }
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            const ck = document.getElementById('confirmKomplainModal');
            if (ck && !ck.classList.contains('hidden')) { closeConfirmKomplain(); return; }
            if (chatMenuId !== null) { closeChatMenu(); return; }
            const dlg = document.getElementById('chat-delete-dialog');
            if (dlg && !dlg.classList.contains('hidden')) { closeDeleteDialog(); return; }
            closeChatModal();
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        const scope = document.querySelector('[data-table-scope]');
        if (!scope) return;

        const rows = Array.from(scope.querySelectorAll('tr[data-table-row], article[data-table-row]'));
        const chipBtns = document.querySelectorAll('#chip-group .chip-btn');
        const searchInput = document.getElementById('komplain-search');
        const clearBtn = document.getElementById('clear-search');
        const countEl = document.getElementById('result-count');
        const emptySearch = document.getElementById('empty-search');
        const emptySearchMobile = document.getElementById('empty-search-mobile');

        const activeClasses = ['bg-deep-onyx', 'text-on-primary', 'border-deep-onyx'];
        const idleClasses = ['border-muted-border', 'text-on-surface-variant'];

        let activeStatus = 'semua';

        function applyFilter() {
            const term = searchInput.value.trim().toLowerCase();
            let visible = 0;

            rows.forEach((row) => {
                const matchStatus = activeStatus === 'semua' || row.getAttribute('data-status') === activeStatus;
                const matchSearch = !term || (row.getAttribute('data-search') || '').includes(term);
                const show = matchStatus && matchSearch;
                row.classList.toggle('hidden', !show);
                if (show) {
                    visible++;
                    const num = row.querySelector('.row-num');
                    if (num) num.textContent = visible;
                }
            });

            countEl.textContent = visible;
            emptySearch.classList.toggle('hidden', visible > 0);
            if (emptySearchMobile) emptySearchMobile.classList.toggle('hidden', visible > 0);
        }

        chipBtns.forEach((btn) => {
            btn.addEventListener('click', () => {
                chipBtns.forEach((b) => {
                    b.classList.remove(...activeClasses);
                    b.classList.add(...idleClasses, 'hover:bg-surface-container-high');
                });
                btn.classList.remove(...idleClasses, 'hover:bg-surface-container-high');
                btn.classList.add(...activeClasses);
                activeStatus = btn.getAttribute('data-chip');
                applyFilter();
            });
        });

        let debounce;
        searchInput.addEventListener('input', () => {
            clearBtn.classList.toggle('opacity-0', !searchInput.value);
            clearTimeout(debounce);
            debounce = setTimeout(applyFilter, 200);
        });

        clearBtn.addEventListener('click', () => {
            searchInput.value = '';
            clearBtn.classList.add('opacity-0');
            applyFilter();
        });

        applyFilter();
    });
</script>
@endpush
