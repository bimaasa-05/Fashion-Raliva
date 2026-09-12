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
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-gutter">
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
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">support_agent</span>
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Ditutup</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">{{ $stats['ditutup'] }}</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">ditutup manual</span>
            </div>
            <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium flex flex-col gap-2 relative overflow-hidden">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Eskalasi</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-error/15 fill pointer-events-none select-none" aria-hidden="true">emergency</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-gold-accent">{{ $stats['escalated'] }}</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">ditonjolkan ke Owner</span>
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
                    <button type="button" data-chip="escalated" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Eskalasi ({{ $stats['escalated'] }})</button>
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
                                @php $chatDone = in_array($c->status, ['selesai', 'ditutup'], true); $chatStatusLabel = $badge['label']; @endphp
                                <div class="flex items-center gap-2 justify-end">
                                    <button type="button" onclick="openChatModal({{ $c->complaint_id }}, '{{ addslashes($c->subjek ?? $c->kategori) }}', '{{ $kode }}', '{{ addslashes($chatStatusLabel) }}', {{ $chatDone ? 'true' : 'false' }})"
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

                    @php $chatDoneM = in_array($c->status, ['selesai', 'ditutup'], true); $chatStatusLabelM = $badge['label']; @endphp
                    <div class="flex gap-gutter">
                        <button type="button" onclick="openChatModal({{ $c->complaint_id }}, '{{ addslashes($c->subjek ?? $c->kategori) }}', '{{ $kode }}', '{{ addslashes($chatStatusLabelM) }}', {{ $chatDoneM ? 'true' : 'false' }})" class="flex-1 min-h-11 inline-flex items-center justify-center gap-2 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:opacity-80 transition-opacity btn-premium">
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

<!-- Chat Komplain Modal (synced identical to Customer) -->
<div class="hidden fixed inset-0 z-[60] bg-black/60 backdrop-blur-sm" id="chat-container" onclick="if(event.target===this) closeChatModal()">
    <div class="min-h-full lg:h-full flex flex-col justify-end lg:flex-row lg:justify-end" onclick="if(event.target===this) closeChatModal()">
        <div id="chat-panel" class="flex flex-col bg-surface-container-low border-t md:border lg:border-t-0 lg:border-l border-[rgba(0,0,0,0.06)] dark:border-[rgba(255,255,255,0.08)] rounded-t-3xl md:rounded-2xl lg:rounded-none max-h-[85dvh] md:max-h-[78dvh] lg:max-h-full lg:h-full w-full md:w-[520px] lg:w-[560px] xl:w-[600px] md:max-w-[88vw] lg:max-w-full md:mx-auto lg:mx-0 overflow-hidden md:shadow-2xl lg:shadow-none" onclick="event.stopPropagation()">
            <div class="relative flex items-center justify-between gap-2 lg:gap-3 pl-6 pr-3 lg:px-6 py-3.5 lg:py-4 border-b border-[rgba(0,0,0,0.06)] dark:border-[rgba(255,255,255,0.08)] shrink-0 bg-surface-container-low z-10 overflow-visible" id="chat-header">
                <div class="min-w-0 flex-1 chat-header-item" id="chat-header-title">
                    <h3 class="font-title-md text-title-md text-on-surface truncate leading-tight" id="chat-subject">-</h3>
                    <p class="font-mono text-on-surface-variant text-xs mt-0.5 truncate" id="chat-kode">-</p>
                </div>
                <div class="flex items-center gap-2 lg:gap-3 shrink-0 chat-header-item" id="chat-header-actions">
                    <span id="chat-status" class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide border bg-surface-container-high text-on-surface-variant border-outline-variant shrink-0 whitespace-nowrap"></span>
                    <button type="button" onclick="toggleChatSearch()" id="chat-search-toggle" class="w-11 h-11 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors cursor-pointer shrink-0" title="Cari pesan" aria-label="Cari pesan">
                        <span class="material-symbols-outlined text-[20px]">search</span>
                    </button>
                    <div class="relative shrink-0" id="chat-more-wrap">
                        <button type="button" onclick="toggleChatMoreMenu()" id="chat-more-btn" class="w-11 h-11 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors cursor-pointer shrink-0" title="Menu" aria-label="Menu">
                            <span class="material-symbols-outlined text-[20px]">more_vert</span>
                        </button>
                        <div id="chat-more-menu" class="hidden absolute right-0 top-full mt-2 min-w-[220px] rounded-xl border border-[rgba(0,0,0,0.06)] dark:border-[rgba(255,255,255,0.08)] bg-surface-container-high shadow-xl z-40 py-1.5">
                            <button type="button" onclick="openWallpaperPicker()" id="chat-more-item-wallpaper" class="w-full text-left px-4 py-2.5 font-body-md text-sm text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer flex items-center gap-2">
                                <span class="material-symbols-outlined text-[19px]">wallpaper</span>Ganti Wallpaper
                            </button>
                            <button type="button" onclick="resetWallpaper()" id="chat-more-item-wp-reset" class="w-full text-left px-4 py-2.5 font-body-md text-sm text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors cursor-pointer flex items-center gap-2">
                                <span class="material-symbols-outlined text-[19px]">restart_alt</span>Reset Wallpaper
                            </button>
                            <button type="button" onclick="selectMessagesMode()" id="chat-more-item-select" class="w-full text-left px-4 py-2.5 font-body-md text-sm text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer flex items-center gap-2">
                                <span class="material-symbols-outlined text-[19px]">check_box</span>Select Messages
                            </button>
                            <button type="button" onclick="openExportChat()" id="chat-more-item-export" class="w-full text-left px-4 py-2.5 font-body-md text-sm text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer flex items-center gap-2">
                                <span class="material-symbols-outlined text-[19px]">ios_share</span>Ekspor Chat
                            </button>
                        </div>
                    </div>
                </div>
                <div id="chat-search-panel" class="absolute inset-0 flex items-center gap-2 lg:gap-3 px-6">
                    <button type="button" id="chat-search-close" onclick="toggleChatSearch()" class="w-11 h-11 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors cursor-pointer shrink-0" title="Tutup pencarian" aria-label="Tutup pencarian">
                        <span class="material-symbols-outlined text-[20px]">search</span>
                    </button>
                    <input id="chat-search-input" type="text" inputmode="search" autocomplete="off" placeholder="Cari pesan..." class="flex-1 min-w-0 bg-transparent font-body-sm text-body-sm text-on-surface placeholder:text-on-surface-variant/70 border-b border-[rgba(0,0,0,0.06)] dark:border-[rgba(255,255,255,0.08)] focus:border-secondary py-2"/>
                    <button type="button" id="chat-search-clear" onclick="clearChatSearch()" class="hidden w-11 h-11 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors cursor-pointer shrink-0" title="Hapus pencarian" aria-label="Hapus pencarian">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                    <span id="chat-search-count" class="font-label-sm text-label-sm text-on-surface-variant shrink-0 hidden"></span>
                </div>
            </div>
            <div class="relative flex-1 flex flex-col min-h-0 overflow-hidden" id="chat-content">
            <div class="relative flex-1 overflow-y-auto px-4 lg:px-6 py-4 lg:py-6 space-y-3 min-h-0 bg-transparent" id="chat-messages">
                <div class="flex justify-center items-center py-8">
                    <div class="w-10 h-10 border-4 border-secondary border-t-transparent rounded-full animate-spin"></div>
                </div>
            </div>
            <div class="relative px-3 lg:px-4 pt-3 pb-[max(0.75rem,env(safe-area-inset-bottom))] pl-[max(0.75rem,env(safe-area-inset-left))] pr-[max(0.75rem,env(safe-area-inset-right))] shrink-0 bg-transparent" id="chat-input-area">
                <div id="chat-emoji-panel" class="hidden absolute bottom-full mb-3 left-3 lg:left-4 z-10 w-[264px] max-w-[calc(100vw-4rem)] lg:w-[320px] max-h-[220px] overflow-y-auto rounded-xl border border-[rgba(0,0,0,0.06)] dark:border-[rgba(255,255,255,0.08)] bg-surface-container-high p-3 shadow-xl"></div>
                <div id="chat-composer" class="flex items-end gap-1 lg:gap-1.5 bg-surface-container-lowest dark:bg-[#1c1c1c] border border-[rgba(0,0,0,0.06)] dark:border-[rgba(255,255,255,0.08)] rounded-[26px] lg:rounded-[28px] px-2 lg:px-2.5 py-2 lg:py-2.5 shadow-sm transition-colors duration-150 focus-within:border-secondary">
                    <button type="button" onclick="toggleEmojiPanel()" id="chat-emoji-toggle" aria-label="Emoji" title="Emoji" class="w-11 h-11 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors cursor-pointer shrink-0">
                        <span class="material-symbols-outlined text-[20px]">mood</span>
                    </button>
                    <textarea id="chat-input" rows="1" maxlength="2000" placeholder="Tulis pesan..."
                        class="flex-1 min-w-0 bg-transparent border-0 outline-none resize-none px-1 py-2.5 font-body-sm text-body-sm text-on-surface placeholder-on-surface-variant"
                        onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();sendMessage();}" aria-label="Tulis pesan"></textarea>
                    <button type="button" onclick="sendMessage()" id="chat-send" aria-label="Kirim pesan" title="Kirim"
                        class="w-11 h-11 flex items-center justify-center bg-secondary text-white shrink-0 hover:opacity-80 active:scale-[0.96] transition-all disabled:opacity-40 rounded-full">
                        <span class="material-symbols-outlined text-[20px]">send</span>
                    </button>
                </div>
                <div id="chat-select-bar" class="items-center gap-2 lg:gap-3 py-1 overflow-x-auto" aria-label="Select messages">
                    <button type="button" onclick="exitSelectMessages()" id="chat-sel-close" class="w-11 h-11 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors cursor-pointer shrink-0" title="Keluar seleksi" aria-label="Keluar seleksi">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                    <span id="chat-sel-count" class="font-body-md text-body-md text-on-surface-variant shrink-0 whitespace-nowrap">0 selected</span>
                    <div class="flex-1 min-w-0"></div>
                    <button type="button" onclick="copySelectedMessages()" id="chat-sel-copy" class="w-11 h-11 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors cursor-pointer shrink-0" title="Salin" aria-label="Salin">
                        <span class="material-symbols-outlined text-[20px]">content_copy</span>
                    </button>
                    <button type="button" onclick="confirmDeleteSelected()" id="chat-sel-delete" class="w-11 h-11 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors cursor-pointer shrink-0" title="Hapus" aria-label="Hapus">
                        <span class="material-symbols-outlined text-[20px]">delete</span>
                    </button>
                    <button type="button" onclick="downloadSelectedMessages()" id="chat-sel-download" class="w-11 h-11 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors cursor-pointer shrink-0" title="Unduh" aria-label="Unduh">
                        <span class="material-symbols-outlined text-[20px]">download</span>
                    </button>
                </div>
                <p id="chat-closed-note" class="hidden text-center font-body-sm text-body-sm text-on-surface-variant pt-4">Komplain telah selesai dan tidak dapat dibalas lagi.</p>
            </div>
            </div><!-- /#chat-content -->
        </div>
    </div>
    <input type="file" id="chat-wallpaper-input" accept="image/*" class="hidden">
    <div id="chat-delete-dialog" class="hidden fixed inset-0 z-[70] flex items-end sm:items-center justify-center bg-black/50" onclick="if(event.target===this){event.stopPropagation();closeDeleteDialog();}">
        <div class="w-full sm:max-w-sm bg-surface-container-low rounded-t-3xl sm:rounded-2xl p-2 sm:p-4 border border-[rgba(0,0,0,0.06)] dark:border-[rgba(255,255,255,0.08)] shadow-2xl" onclick="event.stopPropagation()">
            <p class="font-title-sm text-title-sm text-on-surface px-4 pt-3 pb-2">Hapus pesan ini?</p>
            <button type="button" id="chat-del-opt-all" data-del-per="all" onclick="deleteMessage(deleteDialogMsgId,'all')" class="w-full text-left px-4 py-3 hover:bg-surface-container-high transition-colors cursor-pointer rounded-xl">
                <span class="block font-body-sm text-body-sm text-on-surface">Hapus untuk semua orang</span>
                <span class="block font-body-sm text-body-sm text-on-surface-variant/80">Pesan akan dihapus untuk semua peserta chat ini</span>
            </button>
            <button type="button" data-del-per="me" onclick="deleteMessage(deleteDialogMsgId,'me')" class="w-full text-left px-4 py-3 hover:bg-surface-container-high transition-colors cursor-pointer rounded-xl">
                <span class="block font-body-sm text-body-sm text-on-surface">Hapus untuk diri sendiri</span>
                <span class="block font-body-sm text-body-sm text-on-surface-variant/80">Pesan hanya dihapus dari perangkat Anda</span>
            </button>
            <button type="button" onclick="closeDeleteDialog()" class="w-full text-left px-4 py-3 mt-1 hover:bg-surface-container-high transition-colors cursor-pointer rounded-xl">
                <span class="font-body-sm text-body-sm text-secondary">Batal</span>
            </button>
        </div>
    </div>
    <div id="chat-sel-delete-dialog" class="hidden fixed inset-0 z-[70] flex items-end sm:items-center justify-center bg-black/50" onclick="if(event.target===this){event.stopPropagation();closeSelDeleteDialog();}">
        <div class="w-full sm:max-w-sm bg-surface-container-low rounded-t-3xl sm:rounded-2xl p-2 sm:p-4 border border-[rgba(0,0,0,0.06)] dark:border-[rgba(255,255,255,0.08)] shadow-2xl" onclick="event.stopPropagation()">
            <p class="font-title-sm text-title-sm text-on-surface px-4 pt-3 pb-2">Hapus <span id="chat-sel-del-count" class="text-on-surface">-</span>?</p>
            <p class="font-body-sm text-body-sm text-on-surface-variant px-4 pb-2">Pesan hanya dihapus dari akun Anda.</p>
            <button type="button" data-sel-del-ok onclick="deleteSelectedMessages()" class="w-full text-left px-4 py-3 mt-1 hover:bg-surface-container-high transition-colors cursor-pointer rounded-xl">
                <span class="font-body-sm text-body-sm text-on-surface">Hapus untuk diri sendiri</span>
            </button>
            <button type="button" onclick="closeSelDeleteDialog()" class="w-full text-left px-4 py-3 mt-1 hover:bg-surface-container-high transition-colors cursor-pointer rounded-xl">
                <span class="font-body-sm text-body-sm text-secondary">Batal</span>
            </button>
        </div>
    </div>
    <div id="chat-edit-dialog" class="hidden fixed inset-0 z-[80] flex items-end sm:items-center justify-center bg-black/50" onclick="if(event.target===this){event.stopPropagation();closeEditDialog();}">
        <div class="w-full sm:max-w-lg bg-surface-container-low rounded-t-3xl sm:rounded-2xl border border-[rgba(0,0,0,0.06)] dark:border-[rgba(255,255,255,0.08)] shadow-2xl overflow-hidden flex flex-col max-h-[90dvh]" onclick="event.stopPropagation()">
            <div class="flex items-center gap-3 px-5 py-4 border-b border-[rgba(0,0,0,0.06)] dark:border-[rgba(255,255,255,0.08)] shrink-0">
                <button type="button" onclick="closeEditDialog()" class="p-2 -ml-2 rounded-full text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors cursor-pointer" title="Tutup">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
                <h3 class="font-title-md text-title-md text-on-surface">Edit pesan</h3>
            </div>
            <div id="chat-edit-wallpaper" class="flex-1 min-h-[150px] sm:min-h-[220px] flex items-center justify-end px-6 py-8">
                <div class="max-w-[90%] rounded-xl px-4 py-2.5 bg-secondary text-white">
                    <p class="text-xs mb-1 text-white/60 uppercase tracking-wider">Anda</p>
                    <p id="chat-edit-preview" class="font-body-sm text-body-sm whitespace-pre-wrap break-words">-</p>
                </div>
            </div>
            <div class="relative border-t border-[rgba(0,0,0,0.06)] dark:border-[rgba(255,255,255,0.08)] bg-surface-container-lowest/60 px-5 pt-4 pb-[max(1rem,env(safe-area-inset-bottom))] shrink-0">
                <div id="chat-edit-emoji-panel" class="hidden absolute bottom-full mb-3 left-5 z-10 w-[264px] max-w-[calc(100vw-4rem)] lg:w-[320px] max-h-[220px] overflow-y-auto rounded-xl border border-[rgba(0,0,0,0.06)] dark:border-[rgba(255,255,255,0.08)] bg-surface-container-high p-3 shadow-xl"></div>
                <div class="flex items-end gap-2 lg:gap-3">
                    <button type="button" onclick="toggleEditEmojiPanel()" id="chat-edit-emoji-toggle" class="w-11 h-11 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors cursor-pointer shrink-0" title="Emoji" aria-label="Emoji">
                        <span class="material-symbols-outlined text-[20px]">mood</span>
                    </button>
                    <textarea id="chat-edit-input" rows="1" maxlength="2000" class="flex-1 bg-surface-container-low border border-[rgba(0,0,0,0.06)] dark:border-[rgba(255,255,255,0.08)] rounded-lg px-4 py-3 font-body-sm text-body-sm text-on-surface placeholder-on-surface-variant resize-none focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-colors" onkeydown="if(event.key==='Enter'&&(event.ctrlKey||event.metaKey)){event.preventDefault();saveEditMessage();}"></textarea>
                    <button type="button" onclick="saveEditMessage()" id="chat-edit-save" class="w-11 h-11 lg:w-12 lg:h-12 flex items-center justify-center bg-secondary text-white shrink-0 hover:opacity-80 transition-opacity disabled:opacity-40 rounded-full" title="Simpan">
                        <span class="material-symbols-outlined text-[20px]">check</span>
                    </button>
                </div>
            </div>
        </div>
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
    #chat-messages { scrollbar-width: none; -ms-overflow-style: none; overscroll-behavior: contain; }
    #chat-messages::-webkit-scrollbar { display: none; }
    #chat-emoji-panel { scrollbar-width: none; -ms-overflow-style: none; }
    #chat-emoji-panel::-webkit-scrollbar { display: none; }
    #chat-edit-emoji-panel { scrollbar-width: none; -ms-overflow-style: none; }
    #chat-edit-emoji-panel::-webkit-scrollbar { display: none; }
    .raliva-doodle {
        background-color: var(--surface-container-low);
        background-image: radial-gradient(circle at 1.5px 1.5px, rgba(120, 80, 0, .10) 1.5px, transparent 0);
        background-size: 22px 22px;
    }
    html.theme-dark .raliva-doodle {
        background-image: radial-gradient(circle at 1.5px 1.5px, rgba(255, 255, 255, .07) 1.5px, transparent 0);
    }
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
    #chat-content::before {
        content: '';
        position: absolute;
        inset: 0;
        background: rgba(255,255,255,.04);
        pointer-events: none;
        z-index: 0;
    }
    html.theme-dark #chat-content::before, .dark #chat-content::before { background: rgba(0,0,0,.12); }
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
    .chat-header-item { transition: opacity .3s ease, transform .3s ease; }
    .chat-header-hidden { opacity: 0; transform: translateY(-6px); pointer-events: none; }
    #chat-search-panel { opacity: 0; transform: translateX(20px); pointer-events: none; transition: opacity .3s cubic-bezier(.22,1,.36,1), transform .3s cubic-bezier(.22,1,.36,1); z-index: 30; }
    #chat-search-panel.chat-search-open { opacity: 1; transform: translateX(0); pointer-events: auto; }
    #chat-search-input, #chat-search-input:focus, #chat-search-input:focus-visible, #chat-search-input:active { outline: none !important; box-shadow: none !important; -webkit-appearance: none; appearance: none; }
    #chat-search-input { caret-color: #8B1E3F; }
    #chat-search-input::selection { background: rgba(139,30,63,.55); color: #ffffff; }
    #chat-search-toggle:focus-visible, #chat-search-close:focus-visible, #chat-search-clear:focus-visible { outline: none !important; box-shadow: 0 0 0 2px rgba(139,30,63,.5); border-radius: 9999px; }
    #chat-messages.chat-selecting .chat-sel-box { display: inline-flex; }
    .chat-selecting .chat-msg { cursor: pointer; user-select: none; -webkit-user-select: none; }
    .chat-sel-box {
        display: none;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 6px;
        flex-shrink: 0;
        color: var(--on-surface-variant);
        cursor: pointer;
        transition: transform .2s ease, opacity .2s ease, background-color .2s ease, color .2s ease;
    }
    .chat-sel-box:hover { transform: scale(1.1); }
    .chat-sel-box:active { transform: scale(.92); }
    .chat-sel-box .material-symbols-outlined { font-size: 20px; }
    .chat-msg.sel-selected { background-color: rgba(139, 30, 63, .06); border-radius: 10px; }
    .chat-msg.sel-selected .chat-sel-box { background-color: var(--chrome-accent); color: #fff; }
    #chat-input-area.chat-selecting #chat-composer { display: none; }
    #chat-input-area.chat-selecting #chat-closed-note { display: none; }
    #chat-select-bar { display: none; }
    #chat-input-area.chat-selecting #chat-select-bar { display: flex; }
    #chat-select-bar::-webkit-scrollbar { display: none; }
    #chat-select-bar { scrollbar-width: none; }
    #chat-select-bar.animate-in { animation: chatSelIn .25s ease both; }
    #chat-select-bar.animate-out { animation: chatSelOut .2s ease both; }
    @keyframes chatSelIn { from { transform: translateY(100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    @keyframes chatSelOut { from { transform: translateY(0); opacity: 1; } to { transform: translateY(100%); opacity: 0; } }
    #chat-input, #chat-edit-input {
        resize: none;
        min-height: 40px;
        overflow-y: hidden;
        overflow-x: hidden;
        white-space: pre-wrap;
        overflow-wrap: break-word;
        word-break: break-word;
        scrollbar-width: thin;
    }
    #chat-input {
        background: transparent;
        border: 0;
        outline: none;
        box-shadow: none;
        transition: height 130ms cubic-bezier(.22, 1, .36, 1);
    }
    #chat-input.chat-input--scroll, #chat-edit-input.chat-input--scroll {
        overflow-y: auto;
    }
    #chat-input::-webkit-scrollbar, #chat-edit-input::-webkit-scrollbar {
        width: 4px;
    }
    #chat-input::-webkit-scrollbar-thumb, #chat-edit-input::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, .2);
        border-radius: 9999px;
    }
    html.theme-dark #chat-input::-webkit-scrollbar-thumb,
    html.theme-dark #chat-edit-input::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, .2);
    }
</style>
@endpush

@push('scripts')
<script>
    let currentChat = { id: null, polling: null, done: false, closing: false };
    const myId = {{ Auth::id() }};
    const myRole = "{{ Auth::user()->role->nama_role ?? '' }}";

    function openChatFromCard(el) {
        const card = el.closest('[data-complaint-card]');
        if (!card) return;
        openChatModal(
            card.getAttribute('data-open-id'),
            card.getAttribute('data-open-subjek'),
            card.getAttribute('data-open-kode'),
            card.getAttribute('data-open-statuslabel'),
            card.getAttribute('data-open-done') === '1'
        );
    }

    function openChatModal(id, subjek, kode, statusLabel, done) {
        currentChat.id = id;
        currentChat.done = done;
        currentChat.closing = false;
        chatEditMsgId = null;
        chatSelMode = false;
        chatSelIds.clear();
        document.getElementById('chat-messages').classList.remove('chat-selecting');
        document.getElementById('chat-input-area').classList.remove('chat-selecting');
        closeSelDeleteDialog();
        closeChatMoreMenu();
        closeChatSearch();
        closeChatMenu();
        closeDeleteDialog();
        closeEditDialog();
        closeEmojiPanel();
        document.getElementById('chat-subject').textContent = subjek;
        document.getElementById('chat-kode').textContent = kode;
        const statusEl = document.getElementById('chat-status');
        statusEl.textContent = statusLabel || '';
        statusEl.className = 'shrink-0 inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold uppercase border ' + (done ? 'bg-secondary-container/20 text-secondary border-secondary/20' : 'bg-surface-container-high text-on-surface-variant border-outline-variant');
        document.getElementById('chat-composer').classList.toggle('hidden', done);
        document.getElementById('chat-closed-note').classList.toggle('hidden', !done);
        document.getElementById('chat-messages').innerHTML = '<div class="flex justify-center items-center py-8"><div class="w-10 h-10 border-4 border-secondary border-t-transparent rounded-full animate-spin"></div></div>';

        const container = document.getElementById('chat-container');
        const panel = document.getElementById('chat-panel');
        container.classList.remove('hidden', 'raliva-chat-out');
        panel.classList.remove('raliva-chat-out-sheet');
        void container.offsetWidth;
        container.classList.add('raliva-chat-in');
        panel.classList.add('raliva-chat-in-sheet');
        document.body.style.overflow = 'hidden';

        if (window.autoGrowChatInput) {
            requestAnimationFrame(function () {
                autoGrowChatInput(document.getElementById('chat-input'));
                autoGrowChatInput(document.getElementById('chat-edit-input'));
            });
        }

        loadMessages();
        if (currentChat.polling) clearInterval(currentChat.polling);
        currentChat.polling = setInterval(loadMessages, 5000);
    }

    function closeChatModal() {
        const container = document.getElementById('chat-container');
        if (currentChat.closing || container.classList.contains('hidden')) return;
        currentChat.closing = true;
        exitSelectMessages();
        closeSelDeleteDialog();
        closeChatMoreMenu();
        closeChatSearch();
        closeChatMenu();
        closeDeleteDialog();
        closeEditDialog();
        closeEmojiPanel();
        document.body.style.overflow = '';
        if (currentChat.polling) clearInterval(currentChat.polling);
        currentChat.id = null;

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

    function chatIsAtBottom(el, threshold) {
        threshold = threshold || 80;
        if (!el) return true;
        return el.scrollTop + el.clientHeight >= el.scrollHeight - threshold;
    }

    async function loadMessages() {
        if (!currentChat.id) return;
        const controller = new AbortController();
        const timer = setTimeout(() => controller.abort(), 10000);
        try {
            const url = '{{ route('superadmin.komplain.messages', ':id:') }}'.replace(':id:', currentChat.id);
            const resp = await fetch(url, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                signal: controller.signal
            });
            if (!resp.ok) throw new Error('Gagal memuat pesan');
            const messages = await resp.json();
            const same = messages.length === chatMessages.length &&
                messages.every(function (m, i) {
                    const p = chatMessages[i];
                    return p && m.complaint_message_id === p.complaint_message_id &&
                        m.pesan === p.pesan &&
                        !!m.deleted === !!p.deleted;
                });
            if (same) return;
            renderMessages(messages);
        } catch (err) {
            if (currentChat.id !== null) showChatError(err.name === 'AbortError' ? 'Waktu memuat pesan habis. Coba lagi.' : err.message);
        } finally {
            clearTimeout(timer);
        }
    }

    function showChatError(message) {
        const el = document.getElementById('chat-messages');
        el.innerHTML = '<div class="text-center py-8">' +
            '<p class="text-on-surface-variant text-sm">' + escapeHtml(message) + '</p>' +
            '<p class="text-xs text-on-surface-variant/60 mt-1">Coba muat ulang halaman.</p>' +
            '</div>';
        el.scrollTop = el.scrollHeight;
    }

    function chatMenuMarkup(id, btnColor) {
        return '<span class="relative shrink-0 chat-menu-wrap">' +
            '<button type="button" data-menu-btn="' + id + '" onclick="event.stopPropagation();toggleChatMenu(' + id + ')" class="chat-menu-btn ' + btnColor + ' lg:opacity-0 lg:group-hover:opacity-100 lg:group-focus-within:opacity-100 focus:opacity-100 transition-opacity cursor-pointer rounded-full w-7 h-7 flex items-center justify-center" title="…"><span class="material-symbols-outlined text-[17px]">more_horiz</span></button>' +
            '<span data-menu="' + id + '" class="chat-menu hidden absolute right-0 top-full mt-1 min-w-[150px] max-w-[160px] z-30 rounded-xl border border-[rgba(0,0,0,0.06)] dark:border-[rgba(255,255,255,0.08)] bg-surface-container-high py-1 shadow-xl">';
    }

    function renderMessages(messages) {
        const el = document.getElementById('chat-messages');
        const wasAtBottom = chatIsAtBottom(el);
        const hadMessages = chatMessages.length > 0;
        const prevTop = el.scrollTop;
        chatMessages = messages || [];
        if (!messages || messages.length === 0) {
            el.innerHTML = '<div class="text-center py-10">' +
                '<span class="material-symbols-outlined text-[38px] text-outline-variant inline-block mb-2">chat_bubble_outline</span>' +
                '<p class="font-body-sm text-body-sm text-on-surface-variant">' + escapeHtml('Belum ada pesan. Mulai percakapan.') + '</p>' +
                '</div>';
            el.scrollTop = el.scrollHeight;
            return;
        }

        el.innerHTML = messages.map(function (m) {
            const mine = String(m.sender_id) === String(myId) || (myRole === 'Super Admin' && m.sender?.role === 'Super Admin');
            const sender = mine ? 'Anda' : (m.sender ? m.sender.nama_lengkap : 'Toko');
            const time = mine ? 'text-white/40' : 'text-on-surface-variant/50';
            const bubble = mine ? 'bg-secondary text-white' : 'bg-surface-container-low';
            const meta = mine ? 'text-white/60' : 'text-on-surface-variant';
            const edited = m.edited_at ? ' <span class="italic">(' + escapeHtml('diedit') + ')</span>' : '';
            const actionsOn = !currentChat.done;
            const selBox = '<span class="chat-sel-box" data-sel-box="' + m.complaint_message_id + '" onclick="event.stopPropagation();toggleSelectMessage(' + m.complaint_message_id + ')" aria-hidden="true"><span class="material-symbols-outlined">check_box_outline_blank</span></span>';
            const rowClass = 'flex items-center gap-2 ' + (mine ? (chatSelMode ? 'justify-between' : 'justify-end') : 'justify-start') + ' group chat-msg';
            const selFirst = selBox;
            const selLast = '';

            if (m.deleted) {
                const delBubble = mine ? 'bg-secondary text-white' : 'bg-surface-container-low';
                const delText = mine ? 'text-white' : 'text-error';
                const delBtn = mine ? 'text-white/50 hover:text-white' : 'text-on-surface-variant hover:text-on-surface';
                let delMenu = '';
                if (actionsOn) {
                    delMenu = chatMenuMarkup(m.complaint_message_id, delBtn) +
                        '<button type="button" onclick="event.stopPropagation();openDeleteDialog(' + m.complaint_message_id + ',true)" class="w-full text-left px-4 py-2.5 font-body-sm text-body-sm text-error hover:bg-error/10 transition-colors cursor-pointer flex items-center gap-2"><span class="material-symbols-outlined text-[16px]">delete</span>' + escapeHtml('Hapus pesan') + '</button>' +
                        '</span></span>';
                }
                return '<div class="' + rowClass + '" data-mid="' + m.complaint_message_id + '">' +
                    selFirst +
                    '<div class="max-w-[82%] lg:max-w-[72%] rounded-2xl px-3.5 lg:px-4 pt-2.5 pb-5 relative ' + delBubble + ' shadow-sm" data-bubble>' +
                    '<div class="flex items-center justify-between gap-2">' +
                    '<p class="font-body-sm text-body-sm italic flex items-center gap-1.5 ' + delText + '"><span class="material-symbols-outlined text-[16px] leading-none shrink-0">block</span>' + escapeHtml('Pesan ini telah dihapus') + '</p>' +
                    delMenu +
                    '</div>' +
                    '<span class="absolute bottom-1.5 right-2.5 text-[10px] leading-none ' + time + '">' + formatTime(m.created_at) + '</span>' +
                    '</div>' + selLast + '</div>';
            }

            let menu = '';
            if (actionsOn) {
                const canAll = mine && chatDeleteForAllAllowed(m.created_at);
                const canEdit = mine && chatEditAllowed(m.created_at);
                let items = '';
                if (canEdit) {
                    items += '<button type="button" onclick="event.stopPropagation();openEditDialog(' + m.complaint_message_id + ')" class="w-full text-left px-4 py-2.5 font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low transition-colors cursor-pointer flex items-center gap-2"><span class="material-symbols-outlined text-[16px]">edit</span>' + escapeHtml('Edit pesan') + '</button>';
                }
                items += '<button type="button" onclick="event.stopPropagation();openDeleteDialog(' + m.complaint_message_id + ',' + (canAll ? 'false' : 'true') + ')" class="w-full text-left px-4 py-2.5 font-body-sm text-body-sm text-error hover:bg-error/10 transition-colors cursor-pointer flex items-center gap-2"><span class="material-symbols-outlined text-[16px]">delete</span>' + escapeHtml('Hapus pesan') + '</button>';
                menu = chatMenuMarkup(m.complaint_message_id, mine ? 'text-white/60 hover:text-white' : 'text-on-surface-variant hover:text-on-surface') + items + '</span></span>';
            }

            return '<div class="' + rowClass + '" data-mid="' + m.complaint_message_id + '">' +
                selFirst +
                '<div class="max-w-[82%] lg:max-w-[72%] rounded-2xl px-3.5 lg:px-4 pt-3 pb-5 relative ' + bubble + ' shadow-sm" data-bubble>' +
                '<div class="flex items-start justify-between gap-2 mb-1">' +
                '<p class="text-[11px] ' + meta + ' uppercase tracking-[0.06em] font-medium">' + escapeHtml(sender) + '</p>' +
                menu +
                '</div>' +
                '<p class="font-body-sm text-body-sm whitespace-pre-wrap break-words leading-relaxed" data-pesan>' + escapeHtml(m.pesan) + '</p>' +
                '<span class="absolute bottom-1.5 right-2.5 text-[10px] leading-none ' + time + '">' + formatTime(m.created_at) + edited + '</span>' +
                '</div>' + selLast + '</div>';
        }).join('');
        applySelectionUI();
        if (!hadMessages || wasAtBottom) {
            el.scrollTop = el.scrollHeight;
        } else {
            el.scrollTop = prevTop;
        }
        if (chatSearchOpen) {
            const sInput = document.getElementById('chat-search-input');
            filterChat(sInput ? sInput.value : '');
        }
    }

    function formatTime(value) {
        const d = new Date(value);
        if (isNaN(d.getTime())) return '';
        return d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    }

    function escapeHtml(text) {
        const d = document.createElement('div');
        d.textContent = text;
        return d.innerHTML;
    }

    let chatEditMsgId = null;
    let chatMenuId = null;
    let chatMoreOpen = false;
    let deleteDialogMsgId = null;
    let chatMessages = [];

    function chatEditAllowed(createdAt) {
        const t = new Date(createdAt).getTime();
        if (isNaN(t)) return false;
        return (Date.now() - t) <= 15 * 60 * 1000;
    }

    function chatDeleteForAllAllowed(createdAt) {
        const t = new Date(createdAt).getTime();
        if (isNaN(t)) return false;
        return (Date.now() - t) <= 2 * 24 * 60 * 60 * 1000;
    }

    function toggleChatMenu(id) {
        const menu = document.querySelector('[data-menu="' + id + '"]');
        const btn = document.querySelector('[data-menu-btn="' + id + '"]');
        const panel = document.getElementById('chat-panel');
        if (!menu || !btn || !panel) return;
        const opening = menu.classList.contains('hidden');
        closeChatMenu();
        if (opening) {
            closeChatMoreMenu();
            const btnRect = btn.getBoundingClientRect();
            const panelRect = panel.getBoundingClientRect();
            menu.style.position = 'fixed';
            let top = btnRect.bottom + 6;
            let left = btnRect.right - 150;
            if (left < panelRect.left + 8) left = panelRect.left + 8;
            if (left + 150 > panelRect.right - 8) left = panelRect.right - 158;
            if (top + 80 > panelRect.bottom - 8) {
                top = btnRect.top - 52;
                if (top < panelRect.top + 8) top = panelRect.top + 8;
            }
            if (top + 80 > window.innerHeight - 8) top = window.innerHeight - 88;
            if (top < 8) top = 8;
            menu.style.top = top + 'px';
            menu.style.left = left + 'px';
            menu.style.right = 'auto';
            menu.style.zIndex = '9999';
            menu.style.minWidth = '150px';
            menu.style.maxWidth = '160px';
            menu.classList.remove('hidden');
            chatMenuId = id;
        }
    }

    function closeChatMenu() {
        if (chatMenuId === null) return;
        const menu = document.querySelector('[data-menu="' + chatMenuId + '"]');
        if (menu) {
            menu.classList.add('hidden');
            menu.style.position = '';
            menu.style.top = '';
            menu.style.left = '';
            menu.style.right = '';
            menu.style.zIndex = '';
            menu.style.minWidth = '';
            menu.style.maxWidth = '';
        }
        chatMenuId = null;
    }

    function toggleChatMoreMenu() {
        const menu = document.getElementById('chat-more-menu');
        if (!menu) return;
        const opening = menu.classList.contains('hidden');
        closeChatMoreMenu();
        if (opening) {
            closeChatMenu();
            menu.classList.remove('hidden');
            chatMoreOpen = true;
        }
    }

    function closeChatMoreMenu() {
        if (!chatMoreOpen) return;
        const menu = document.getElementById('chat-more-menu');
        if (menu) menu.classList.add('hidden');
        chatMoreOpen = false;
    }

    let chatSearchOpen = false;

    function toggleChatSearch() {
        const panel = document.getElementById('chat-search-panel');
        const input = document.getElementById('chat-search-input');
        if (!panel) return;
        if (chatSearchOpen) { closeChatSearch(); return; }
        chatSearchOpen = true;
        closeChatMoreMenu();
        closeChatMenu();
        panel.classList.add('chat-search-open');
        document.querySelectorAll('#chat-header .chat-header-item').forEach(function (h) { h.classList.add('chat-header-hidden'); });
        if (input) input.focus();
    }

    function closeChatSearch() {
        const panel = document.getElementById('chat-search-panel');
        chatSearchOpen = false;
        if (panel) panel.classList.remove('chat-search-open');
        document.querySelectorAll('#chat-header .chat-header-item').forEach(function (h) { h.classList.remove('chat-header-hidden'); });
        const input = document.getElementById('chat-search-input');
        if (input) input.value = '';
        filterChat('');
    }

    function clearChatSearch() {
        const input = document.getElementById('chat-search-input');
        if (input) input.value = '';
        filterChat('');
        if (input) input.focus();
    }

    function filterChat(q) {
        q = (q || '').trim().toLowerCase();
        const el = document.getElementById('chat-messages');
        const rows = el ? Array.prototype.slice.call(el.querySelectorAll('[data-mid]')) : [];
        let shown = 0;
        rows.forEach(function (row) {
            const pesanEl = row.querySelector('[data-pesan]');
            const text = pesanEl ? pesanEl.textContent : (row.textContent || '');
            const ok = !q || text.toLowerCase().indexOf(q) >= 0;
            row.style.display = ok ? '' : 'none';
            if (ok) shown++;
        });
        const clearBtn = document.getElementById('chat-search-clear');
        const countEl = document.getElementById('chat-search-count');
        if (clearBtn) clearBtn.classList.toggle('hidden', !q);
        if (countEl) {
            countEl.textContent = shown + ' / ' + rows.length;
            countEl.classList.toggle('hidden', !q);
        }
        if (el) {
            let noResults = document.getElementById('chat-search-noresults');
            if (!noResults) {
                noResults = document.createElement('p');
                noResults.id = 'chat-search-noresults';
                noResults.className = 'hidden text-center font-body-sm text-body-sm text-on-surface-variant py-8';
                noResults.textContent = 'Tidak ada pesan yang cocok.';
                el.appendChild(noResults);
            }
            noResults.classList.toggle('hidden', shown > 0 || rows.length === 0 || !q);
        }
    }

    (function bindChatSearch() {
        const input = document.getElementById('chat-search-input');
        if (!input) return;
        input.addEventListener('input', function () { filterChat(input.value); });
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') { e.stopPropagation(); closeChatSearch(); }
        });
    })();

    function showChatToast(message) {
        var existing = document.getElementById('chat-toast');
        if (existing) existing.remove();
        var toast = document.createElement('div');
        toast.id = 'chat-toast';
        toast.textContent = message;
        toast.style.cssText = 'position:fixed;left:50%;bottom:96px;transform:translateX(-50%);background:#1c1b1b;color:#fff;padding:10px 18px;border-radius:999px;font-size:13px;font-family:Manrope,sans-serif;z-index:9999;box-shadow:0 8px 24px rgba(0,0,0,.25);opacity:0;transition:opacity .3s ease;';
        document.body.appendChild(toast);
        requestAnimationFrame(function () { toast.style.opacity = '1'; });
        setTimeout(function () { toast.style.opacity = '0'; setTimeout(function () { toast.remove(); }, 350); }, 2200);
    }

    let chatSelMode = false;
    let chatSelIds = new Set();

    function openWallpaperPicker() {
        closeChatMoreMenu();
        const input = document.getElementById('chat-wallpaper-input');
        if (input) input.click();
    }

    function applyWallpaper(url, persist) {
        const layer = document.getElementById('chat-content') || document.getElementById('chat-messages');
        const editLayer = document.getElementById('chat-edit-wallpaper');
        [layer, editLayer].forEach(function (el) {
            if (!el) return;
            if (url) {
                el.style.backgroundImage = "url('" + url.replace(/'/g, "\\'") + "')";
                el.style.backgroundSize = 'cover';
                el.style.backgroundPosition = 'center';
            } else {
                el.style.backgroundImage = '';
                el.style.backgroundSize = '';
                el.style.backgroundPosition = '';
            }
        });
        if (persist) {
            try {
                localStorage.setItem('raliva_chat_wallpaper', url || '');
            } catch (_) {}
        }
    }

    function resetWallpaper() {
        applyWallpaper('', true);
        showChatToast('Wallpaper direset ke default.');
    }

    function initWallpaper() {
        let saved = '';
        try { saved = localStorage.getItem('raliva_chat_wallpaper') || ''; } catch (_) {}
        if (saved) applyWallpaper(saved, false);
    }

    document.addEventListener('change', function (ev) {
        if (ev.target && ev.target.id !== 'chat-wallpaper-input') return;
        const file = ev.target.files && ev.target.files[0];
        ev.target.value = '';
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function () {
            applyWallpaper(reader.result, true);
            showChatToast('Wallpaper diperbarui.');
        };
        reader.readAsDataURL(file);
    });

    function selectMessagesMode() {
        closeChatMoreMenu();
        closeChatSearch();
        closeChatMenu();
        if (currentChat.done) {
            showChatToast('Pesan komplain yang sudah selesai tidak dapat dipilih.');
            return;
        }
        chatSelMode = true;
        chatSelIds.clear();
        document.getElementById('chat-messages').classList.add('chat-selecting');
        const bar = document.getElementById('chat-select-bar');
        const area = document.getElementById('chat-input-area');
        area.classList.add('chat-selecting');
        bar.classList.remove('animate-out');
        bar.classList.add('animate-in');
        renderMessages(chatMessages);
        applySelectionUI();
    }

    function exitSelectMessages() {
        if (!chatSelMode) return;
        chatSelMode = false;
        chatSelIds.clear();
        const area = document.getElementById('chat-input-area');
        const bar = document.getElementById('chat-select-bar');
        bar.classList.remove('animate-in');
        bar.classList.add('animate-out');
        setTimeout(function () {
            area.classList.remove('chat-selecting');
            bar.classList.remove('animate-out');
        }, 200);
        document.getElementById('chat-messages').classList.remove('chat-selecting');
        renderMessages(chatMessages);
        applySelectionUI();
    }

    function toggleSelectMessage(id) {
        if (!chatSelMode) return;
        id = parseInt(id, 10);
        if (chatSelIds.has(id)) chatSelIds.delete(id); else chatSelIds.add(id);
        applySelectionUI();
    }

    function applySelectionUI() {
        const el = document.getElementById('chat-messages');
        const count = chatSelIds.size;
        const countEl = document.getElementById('chat-sel-count');
        if (countEl) countEl.textContent = count + ' selected';
        if (!el) return;
        Array.prototype.forEach.call(el.querySelectorAll('[data-mid]'), function (row) {
            const id = parseInt(row.getAttribute('data-mid'), 10);
            const selected = chatSelMode && chatSelIds.has(id);
            row.classList.toggle('sel-selected', selected);
            const box = row.querySelector('[data-sel-box]');
            if (box) {
                const icon = box.querySelector('.material-symbols-outlined');
                if (icon) icon.textContent = selected ? 'check_box' : 'check_box_outline_blank';
            }
        });
    }

    function copySelectedMessages() {
        if (chatSelIds.size === 0) { showChatToast('Pilih minimal satu pesan.'); return; }
        const rows = chatMessages.filter(function (m) {
            return chatSelIds.has(parseInt(m.complaint_message_id, 10)) && m.pesan;
        });
        const text = rows.map(function (m) {
            const isMine = String(m.sender_id) === String(myId) || (myRole === 'Super Admin' && m.sender?.role === 'Super Admin');
            const sender = isMine ? 'Anda' : (m.sender ? m.sender.nama_lengkap : 'Toko');
            return '[' + sender + '] ' + new Date(m.created_at).toLocaleString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) + '\n' + m.pesan;
        }).join('\n\n');
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(function () {
                showChatToast('Pesan tersalin ke clipboard.');
            });
        } else {
            const ta = document.createElement('textarea');
            ta.value = text;
            ta.style.position = 'fixed';
            ta.style.opacity = '0';
            document.body.appendChild(ta);
            ta.select();
            try { document.execCommand('copy'); showChatToast('Pesan tersalin ke clipboard.'); } catch (_) {}
            ta.remove();
        }
    }

    function confirmDeleteSelected() {
        if (chatSelIds.size === 0) { showChatToast('Pilih minimal satu pesan.'); return; }
        const el = document.getElementById('chat-sel-delete-dialog');
        document.getElementById('chat-sel-del-count').textContent = chatSelIds.size + ' pesan';
        el.classList.remove('hidden');
    }

    function closeSelDeleteDialog() {
        document.getElementById('chat-sel-delete-dialog').classList.add('hidden');
    }

    async function deleteSelectedMessages() {
        const ids = Array.from(chatSelIds);
        if (ids.length === 0) return;
        const btn = document.querySelector('#chat-sel-delete-dialog [data-sel-del-ok]');
        if (btn) btn.disabled = true;
        let failed = 0;
        for (const id of ids) {
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
                if (!resp.ok) failed++;
            } catch (_) { failed++; }
        }
        closeSelDeleteDialog();
        if (btn) btn.disabled = false;
        exitSelectMessages();
        loadMessages();
        if (failed === 0) showChatToast(ids.length + ' pesan dihapus untuk diri sendiri.');
        else showChatToast(failed + ' pesan gagal dihapus.');
    }

    function downloadSelectedMessages() {
        showChatToast('Fitur akan segera hadir.');
    }

    function openExportChat() {
        closeChatMoreMenu();
        showChatToast('Fitur akan segera hadir.');
    }

    function openEditDialog(id) {
        closeChatMenu();
        const msg = chatMessages.find(function (m) {
            return parseInt(m.complaint_message_id, 10) === parseInt(id, 10);
        });
        if (!msg) return;
        chatEditMsgId = id;
        const input = document.getElementById('chat-edit-input');
        const preview = document.getElementById('chat-edit-preview');
        const saveBtn = document.getElementById('chat-edit-save');
        input.value = msg.pesan || '';
        if (preview) preview.textContent = msg.pesan || '';
        input.disabled = false;
        if (saveBtn) saveBtn.disabled = false;
        document.getElementById('chat-edit-dialog').classList.remove('hidden');
        if (window.autoGrowChatInput) {
            requestAnimationFrame(function () { autoGrowChatInput(input); });
        }
        setTimeout(function () {
            input.focus();
            input.setSelectionRange(input.value.length, input.value.length);
        }, 30);
    }

    function closeEditDialog() {
        closeEditEmojiPanel();
        chatEditMsgId = null;
        const editInput = document.getElementById('chat-edit-input');
        if (editInput) editInput.style.height = '';
        document.getElementById('chat-edit-dialog').classList.add('hidden');
    }

    async function saveEditMessage() {
        const id = chatEditMsgId;
        if (!id) return;
        const input = document.getElementById('chat-edit-input');
        const pesan = input.value.trim();
        if (pesan.length < 3) { input.focus(); return; }
        input.disabled = true;
        document.getElementById('chat-edit-save').disabled = true;
        try {
            const url = '{{ route('superadmin.komplain.messages.update', [':cid:', ':mid:']) }}'.replace(':cid:', currentChat.id).replace(':mid:', id);
            const resp = await fetch(url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ pesan })
            });
            if (resp.ok) {
                closeEditDialog();
                await loadMessages();
            } else {
                let msg = 'Gagal menyimpan perubahan';
                try {
                    const data = await resp.json();
                    if (data && data.message) msg = data.message;
                    else if (data && data.errors) msg = Object.values(data.errors).flat().join('\n');
                } catch (_) {}
                alert(msg);
                input.disabled = false;
                document.getElementById('chat-edit-save').disabled = false;
                input.focus();
            }
        } catch (_) {
            input.disabled = false;
            document.getElementById('chat-edit-save').disabled = false;
            input.focus();
        }
    }

    function openDeleteDialog(id, onlyMe) {
        closeChatMenu();
        deleteDialogMsgId = id;
        const optAll = document.getElementById('chat-del-opt-all');
        if (optAll) optAll.classList.toggle('hidden', !!onlyMe);
        document.querySelectorAll('#chat-delete-dialog [data-del-per]').forEach(function (b) { b.disabled = false; });
        document.getElementById('chat-delete-dialog').classList.remove('hidden');
    }

    function closeDeleteDialog() {
        deleteDialogMsgId = null;
        document.querySelectorAll('#chat-delete-dialog [data-del-per]').forEach(function (b) { b.disabled = false; });
        document.getElementById('chat-delete-dialog').classList.add('hidden');
    }

    async function deleteMessage(id, per) {
        if (!id) return;
        const btnEl = document.querySelector('#chat-delete-dialog [data-del-per="' + per + '"]');
        if (btnEl) btnEl.disabled = true;
        try {
            const url = '{{ route('superadmin.komplain.messages.destroy', [':cid:', ':mid:']) }}'.replace(':cid:', currentChat.id).replace(':mid:', id);
            const resp = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ per })
            });
            closeDeleteDialog();
            if (resp.ok) {
                await loadMessages();
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
        } finally {
            if (btnEl) btnEl.disabled = false;
        }
    }

    const CHAT_EMOJI = ['😀','😁','😂','🤣','😊','😍','🥰','😘','😚','😜','🤪','😎','🥸','🤗','🤭','🫢','😇','🥺','🤔','🤨','😐','😑','😶','🙄','😏','😮','😯','😪','😴','🤤','😌','😢','😭','😅','😆','😉','🙃','😬','👍','👎','👌','✌️','🤞','🤝','🙏','👏','🙌','💪','🤙','👋','❤️','🧡','💛','💚','💙','💜','🖤','🤍','💖','💘','💯','🔥','✨','⭐','🎉','🎁','🎊','👀'];

    function renderEmojiPanelOf(panelId, inputId) {
        const panel = document.getElementById(panelId);
        if (!panel || panel.dataset.rendered) return;
        panel.dataset.inputId = inputId;
        panel.innerHTML = '<div class="grid grid-cols-8 gap-1">' + CHAT_EMOJI.map(function (e) {
            return '<button type="button" data-emoji="' + e + '" onclick="insertEmojiTo(this)" class="w-9 h-9 flex items-center justify-center text-[20px] leading-none rounded-lg hover:bg-surface-container-low transition-colors cursor-pointer">' + e + '</button>';
        }).join('') + '</div>';
        panel.dataset.rendered = '1';
    }

    function renderEmojiPanel() { renderEmojiPanelOf('chat-emoji-panel', 'chat-input'); }

    function toggleEmojiPanel() {
        renderEmojiPanel();
        const panel = document.getElementById('chat-emoji-panel');
        const btn = document.getElementById('chat-emoji-toggle');
        if (!panel) return;
        const open = panel.classList.toggle('hidden') === false;
        if (btn) {
            btn.classList.toggle('text-secondary', open);
            btn.classList.toggle('bg-surface-container-high', open);
        }
    }

    function closeEmojiPanel() {
        const panel = document.getElementById('chat-emoji-panel');
        if (!panel || panel.classList.contains('hidden')) return;
        panel.classList.add('hidden');
        const btn = document.getElementById('chat-emoji-toggle');
        if (btn) btn.classList.remove('text-secondary', 'bg-surface-container-high');
    }

    function toggleEditEmojiPanel() {
        renderEmojiPanelOf('chat-edit-emoji-panel', 'chat-edit-input');
        const panel = document.getElementById('chat-edit-emoji-panel');
        const btn = document.getElementById('chat-edit-emoji-toggle');
        if (!panel) return;
        const open = panel.classList.toggle('hidden') === false;
        if (btn) {
            btn.classList.toggle('text-secondary', open);
            btn.classList.toggle('bg-surface-container-high', open);
        }
    }

    function closeEditEmojiPanel() {
        const panel = document.getElementById('chat-edit-emoji-panel');
        if (!panel || panel.classList.contains('hidden')) return;
        panel.classList.add('hidden');
        const btn = document.getElementById('chat-edit-emoji-toggle');
        if (btn) btn.classList.remove('text-secondary', 'bg-surface-container-high');
    }

    (function () {
        function getChatMetrics(el) {
            const cs = getComputedStyle(el);
            const lh = parseFloat(cs.lineHeight) || 20;
            const pad = (parseFloat(cs.paddingTop) || 0) + (parseFloat(cs.paddingBottom) || 0);
            const border = (parseFloat(cs.borderTopWidth) || 0) + (parseFloat(cs.borderBottomWidth) || 0);
            const isBorderBox = cs.boxSizing === 'border-box';
            const maxScroll = lh * 5 + pad;
            const maxHeight = isBorderBox ? maxScroll + border : maxScroll;
            const minHeight = Math.min(maxHeight, lh + pad + (isBorderBox ? border : 0));
            return { lh, pad, border, isBorderBox, maxScroll, maxHeight, minHeight };
        }
        function autoGrow(el) {
            if (!el) return;
            const m = getChatMetrics(el);
            el.style.height = 'auto';
            let h = el.scrollHeight + (m.isBorderBox ? m.border : 0);
            if (h <= m.maxHeight) {
                el.style.height = (h < m.minHeight ? m.minHeight : h) + 'px';
                el.classList.remove('chat-input--scroll');
            } else {
                el.style.height = m.maxHeight + 'px';
                el.classList.add('chat-input--scroll');
            }
        }
        ['chat-input', 'chat-edit-input'].forEach(function (id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.addEventListener('input', function () { autoGrow(el); });
            el.addEventListener('paste', function () { requestAnimationFrame(function () { autoGrow(el); }); });
            if (el.offsetParent !== null) {
                autoGrow(el);
            } else {
                const m = getChatMetrics(el);
                el.style.height = m.minHeight + 'px';
            }
        });
        window.autoGrowChatInput = autoGrow;
    })();

    function insertEmojiTo(btn) {
        const panel = btn.closest('[data-input-id]');
        const input = panel ? document.getElementById(panel.dataset.inputId) : null;
        const emoji = btn.getAttribute('data-emoji');
        if (!input || !emoji) return;
        const start = input.selectionStart != null ? input.selectionStart : input.value.length;
        const end = input.selectionEnd != null ? input.selectionEnd : start;
        const next = input.value.slice(0, start) + emoji + input.value.slice(end);
        input.value = next.slice(0, 2000);
        if (typeof input.dispatchEvent === 'function') {
            input.dispatchEvent(new Event('input', { bubbles: true }));
        }
        const pos = start + emoji.length;
        input.focus();
        input.setSelectionRange(pos, pos);
    }

    // Klik di dalam modal (wallpaper, pesan, input) tapi di luar form → tutup form (titik tiga header/chat + search + emoji)
    document.getElementById('chat-content')?.addEventListener('click', function (e) {
        if (chatMoreOpen && !e.target.closest('#chat-more-wrap')) closeChatMoreMenu();
        if (chatMenuId !== null && !e.target.closest('[data-menu]') && !e.target.closest('[data-menu-btn]')) closeChatMenu();
        if (chatSearchOpen && !e.target.closest('#chat-search-panel') && !e.target.closest('#chat-search-toggle')) closeChatSearch();
        const ep = document.getElementById('chat-emoji-panel');
        if (ep && !ep.classList.contains('hidden') && !e.target.closest('#chat-emoji-panel') && !e.target.closest('#chat-emoji-toggle')) closeEmojiPanel();
        const eep = document.getElementById('chat-edit-emoji-panel');
        if (eep && !eep.classList.contains('hidden') && !e.target.closest('#chat-edit-emoji-panel') && !e.target.closest('#chat-edit-emoji-toggle')) closeEditEmojiPanel();
    });
    document.getElementById('chat-messages')?.addEventListener('click', function (e) {
        if (chatMoreOpen) closeChatMoreMenu();
        if (chatMenuId !== null && !e.target.closest('[data-menu]') && !e.target.closest('[data-menu-btn]')) closeChatMenu();
        const ep2 = document.getElementById('chat-emoji-panel');
        if (ep2 && !ep2.classList.contains('hidden') && !e.target.closest('#chat-emoji-panel') && !e.target.closest('#chat-emoji-toggle')) closeEmojiPanel();
    });
    document.addEventListener('click', function (ev) {
        if (ev.target.closest) {
            if (chatSearchOpen && !ev.target.closest('#chat-search-panel') && !ev.target.closest('#chat-search-toggle') && !ev.target.closest('#chat-search-close')) closeChatSearch();
            if (chatMoreOpen && !ev.target.closest('#chat-more-wrap')) closeChatMoreMenu();
            if (chatSelMode) {
                const row = ev.target.closest('.chat-msg');
                if (row && !ev.target.closest('[data-sel-box]') && !ev.target.closest('button') && !ev.target.closest('.chat-menu') && !ev.target.closest('#chat-more-wrap')) {
                    toggleSelectMessage(row.getAttribute('data-mid'));
                    return;
                }
            }
            if (chatMenuId !== null) {
                const inMenu = ev.target.closest('[data-menu="' + chatMenuId + '"]') ||
                    ev.target.closest('[data-menu-btn="' + chatMenuId + '"]') ||
                    ev.target.closest('[data-mid="' + chatMenuId + '"]');
                if (!inMenu) closeChatMenu();
            }
            const ep = document.getElementById('chat-edit-emoji-panel');
            if (ep && !ep.classList.contains('hidden') &&
                !ev.target.closest('#chat-edit-emoji-panel') &&
                !ev.target.closest('#chat-edit-emoji-toggle')) {
                closeEditEmojiPanel();
            }
        }
        const panel = document.getElementById('chat-emoji-panel');
        if (!panel || panel.classList.contains('hidden')) return;
        if (ev.target.closest && (ev.target.closest('#chat-emoji-panel') || ev.target.closest('#chat-emoji-toggle'))) return;
        closeEmojiPanel();
    });

    const chatEditInputEl = document.getElementById('chat-edit-input');
    if (chatEditInputEl) {
        chatEditInputEl.addEventListener('input', function () {
            const preview = document.getElementById('chat-edit-preview');
            if (preview) preview.textContent = this.value;
        });
    }

    initWallpaper();

    async function sendMessage() {
        const composer = document.getElementById('chat-composer');
        if (composer.classList.contains('hidden')) return;
        const input = document.getElementById('chat-input');
        const pesan = input.value.trim();
        if (!pesan || !currentChat.id) return;

        document.getElementById('chat-send').disabled = true;
        input.value = '';
        if (window.autoGrowChatInput) autoGrowChatInput(input);

        try {
            const url = '{{ route('superadmin.komplain.messages.store', ':id:') }}'.replace(':id:', currentChat.id);
            const resp = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ pesan })
            });
            if (resp.ok) {
                await loadMessages();
                if (currentChat.done) {
                    currentChat.done = false;
                    document.getElementById('chat-composer').classList.remove('hidden');
                    document.getElementById('chat-closed-note').classList.add('hidden');
                }
            } else {
                input.value = pesan;
                if (window.autoGrowChatInput) requestAnimationFrame(function () { autoGrowChatInput(input); });
                let msg = 'Gagal mengirim pesan';
                try {
                    const data = await resp.json();
                    if (data && data.errors) msg = Object.values(data.errors).flat().join('\n');
                    else if (data && data.message) msg = data.message;
                } catch (_) {}
                alert(msg);
            }
        } catch (_) {
            input.value = pesan;
            if (window.autoGrowChatInput) requestAnimationFrame(function () { autoGrowChatInput(input); });
        } finally {
            document.getElementById('chat-send').disabled = false;
        }
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            if (chatSearchOpen) { closeChatSearch(); return; }
            if (chatSelMode) { exitSelectMessages(); return; }
            if (chatMoreOpen) { closeChatMoreMenu(); return; }
            const ep = document.getElementById('chat-edit-emoji-panel');
            if (ep && !ep.classList.contains('hidden')) { closeEditEmojiPanel(); return; }
            const ed = document.getElementById('chat-edit-dialog');
            if (ed && !ed.classList.contains('hidden')) { closeEditDialog(); return; }
            if (chatMenuId !== null) { closeChatMenu(); return; }
            const dialog = document.getElementById('chat-delete-dialog');
            if (dialog && !dialog.classList.contains('hidden')) { closeDeleteDialog(); return; }
            const panel = document.getElementById('chat-emoji-panel');
            if (panel && !panel.classList.contains('hidden')) { closeEmojiPanel(); return; }
            const ck = document.getElementById('confirmKomplainModal');
            if (ck && !ck.classList.contains('hidden')) { closeConfirmKomplain(); return; }
            closeChatModal();
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        var params = new URLSearchParams(window.location.search);
        var openId = params.get('open');
        if (openId) {
            var card = document.querySelector('[data-open-id="' + openId + '"]');
            if (card) openChatFromCard(card);
        }
    });

    // SuperAdmin extras: confirm komplain + table filter
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
        const toggle = document.querySelector('[data-filter-toggle]');
        const panel = document.querySelector('[data-filter-panel]');
        if (toggle && panel) {
            toggle.addEventListener('click', () => {
                panel.classList.toggle('hidden');
                const chev = toggle.querySelector('[data-filter-chevron]');
                if (chev) chev.classList.toggle('rotate-180');
            });
        }
    });
</script>
@endpush

