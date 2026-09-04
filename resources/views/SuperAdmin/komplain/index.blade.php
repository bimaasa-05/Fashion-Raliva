@extends('layouts.superadmin')

@section('title', 'Komplain')

@section('header-title', 'Komplain')
@section('header-badge', 'Pantau')

@section('header-subtitle', 'Monitor dan tangani komplain Customer terhadap toko.')

@php
    $badgeMap = [
        'open' => ['label' => 'Terbuka', 'class' => 'bg-gold-accent/10 text-gold-accent border-gold-accent/20'],
        'diproses' => ['label' => 'Diproses', 'class' => 'bg-surface-container-high text-on-surface border-outline-variant'],
        'selesai' => ['label' => 'Selesai', 'class' => 'bg-secondary-container/20 text-secondary border-secondary/20'],
        'ditutup' => ['label' => 'Ditutup', 'class' => 'bg-error/10 text-error border-error/20'],
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
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Daftar Komplain</h2>

        <!-- Filters -->
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
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center gap-3">
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                <input id="komplain-search" class="w-full bg-surface-container-low border border-muted-border rounded-lg pl-11 pr-10 py-3 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" type="text" placeholder="Cari ID komplain, nama pelanggan, atau toko..." />
                <button type="button" id="clear-search" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-gold-accent opacity-0 transition-opacity">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            <p class="text-on-surface-variant font-body-md text-xs shrink-0">
                <span id="result-count">{{ $complaints->count() }}</span> komplain
            </p>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
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
                                <p class="font-title-md text-title-md text-on-surface">{{ $c->subject }}</p>
                                <p class="font-mono text-xs text-on-surface-variant">{{ $kode }}</p>
                            </td>
                            <td class="p-6">
                                <p class="text-on-surface">{{ $c->store->nama_toko }}</p>
                                <p class="text-on-surface-variant text-xs">{{ $c->store->owner->nama_lengkap ?? '-' }}</p>
                            </td>
                            <td class="p-6">
                                <span class="inline-flex items-center px-2 py-1 rounded {{ $badge['class'] }} text-xs uppercase">{{ $badge['label'] }}</span>
                            </td>
                            <td class="p-6 text-xs">{{ $c->tipe_komplain }}</td>
                            <td class="p-6 text-xs text-on-surface-variant">
                                {{ $c->dibuat_pada ? \Carbon\Carbon::parse($c->dibuat_pada)->locale('id')->diffForHumans() : '-' }}
                            </td>
                            <td class="p-6">
                                <div class="flex items-center gap-2 justify-end">
                                    <button type="button" onclick="openChatModal({{ $c->complaint_id }}, '{{ $kode }}', '{{ addslashes($c->subject) }}', {{ $c->eskalasi_oleh_sa ? 'true' : 'false' }})"
                                        class="flex items-center gap-1 px-3 py-1.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:opacity-80 transition-opacity btn-premium">
                                        <span class="material-symbols-outlined text-sm">chat</span>
                                        Buka
                                    </button>

                                    @if (in_array($c->status, [\App\Models\Complaint::STATUS_OPEN, \App\Models\Complaint::STATUS_DIPROSES]))
                                        @if (! $c->eskalasi_oleh_sa)
                                            <form method="POST" action="{{ route('superadmin.komplain.eskalasi', $c->complaint_id) }}" class="inline-block">
                                                @csrf
                                                <button type="submit" title="Eskalasi"
                                                    class="w-8 h-8 flex items-center justify-center bg-gold-accent/10 text-gold-accent border border-gold-accent/25 hover:bg-gold-accent hover:text-on-gold-accent transition-colors">
                                                    <span class="material-symbols-outlined text-sm">emergency</span>
                                                </button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('superadmin.komplain.tutup', $c->complaint_id) }}" class="inline-block">
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
    </section>
</div>

<!-- Chat Komplain Modal -->
<div class="hidden fixed inset-0 z-[55] bg-black/60 backdrop-blur-sm"
     id="chat-container" onclick="if(event.target===this) closeChatModal()">

    <div class="p-4 lg:p-8 flex items-end justify-end">
        <button type="button" onclick="closeChatModal()" class="p-3 rounded-full bg-surface-container-high/80 text-on-surface hover:bg-surface-container-high transition-colors lg:mt-4" title="Tutup">
            <span class="material-symbols-outlined text-[20px]">close</span>
        </button>
    </div>

    <div class="flex flex-col bg-surface-container-low border-l border-muted-border lg:h-full overflow-hidden" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between px-6 py-4 border-b border-muted-border">
            <div>
                <h3 class="font-title-lg text-title-lg text-on-surface" id="chat-subject">-</h3>
                <p class="font-mono text-on-surface-variant text-xs" id="chat-kode">-</p>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto px-6 py-6 space-y-4" id="chat-messages">
            <div class="flex justify-center items-center py-8">
                <div class="w-10 h-10 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
            </div>
        </div>

        <div class="px-6 py-4 border-t border-muted-border bg-surface-container-lowest/60 hidden" id="chat-input-area">
            <div class="flex items-end gap-3">
                <textarea id="chat-input" rows="1" maxlength="2000" placeholder="Tulis pesan..."
                    class="flex-1 bg-surface-container-low border border-muted-border rounded-lg px-4 py-3 font-body-md text-body-md text-on-surface placeholder-on-surface-variant/50 resize-none focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                    onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();sendMessage();}"></textarea>
                <button type="button" onclick="sendMessage()" id="chat-send"
                    class="w-12 h-12 flex items-center justify-center bg-gold-accent text-on-gold-accent shrink-0 hover:opacity-80 transition-opacity btn-premium disabled:opacity-40">
                    <span class="material-symbols-outlined text-[20px]">send</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentChat = { id: null, polling: null };
    const myId = {{ Auth::id() }};

    function openChatModal(id, kode, subject, isOpen) {
        currentChat.id = id;
        document.getElementById('chat-kode').textContent = kode;
        document.getElementById('chat-subject').textContent = subject;
        const inputArea = document.getElementById('chat-input-area');
        inputArea.classList.add('hidden');

        const container = document.getElementById('chat-container');
        container.classList.remove('hidden');
        if (window.innerWidth >= 1024) container.style.display = 'grid';
        container.style.gridTemplateColumns = '1fr 560px';
        document.body.style.overflow = 'hidden';

        loadMessages();

        if (currentChat.polling) clearInterval(currentChat.polling);
        currentChat.polling = setInterval(loadMessages, 5000);
    }

    function closeChatModal() {
        const container = document.getElementById('chat-container');
        container.classList.add('hidden');
        container.style.display = '';
        container.style.gridTemplateColumns = '';
        document.body.style.overflow = '';
        if (currentChat.polling) clearInterval(currentChat.polling);
        currentChat.id = null;
    }

    async function loadMessages() {
        if (!currentChat.id) return;
        try {
            const resp = await fetch(`/komplain/${currentChat.id}/messages`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!resp.ok) return;
            const messages = await resp.json();
            renderMessages(messages);
        } catch (_) {}
    }

    function renderMessages(messages) {
        const el = document.getElementById('chat-messages');
        el.innerHTML = messages.map(m => `
            <div class="flex ${m.sender_id === myId ? 'justify-end' : 'justify-start'}">
                <div class="max-w-[80%] rounded-xl p-4 ${m.sender_id === myId ? 'bg-deep-onyx text-on-primary' : 'bg-surface-container-high text-on-surface'}">
                    <p class="text-xs mb-1 ${m.sender_id === myId ? 'text-on-primary/60' : 'text-on-surface-variant'}">
                        ${m.sender_id === myId ? 'Super Admin' : (m.sender?.role === 'customer' ? m.sender?.nama_lengkap : 'Toko')}
                    </p>
                    <p class="font-body-md text-sm whitespace-pre-wrap">${escapeHtml(m.pesan)}</p>
                    <p class="text-[10px] mt-2 ${m.sender_id === myId ? 'text-on-primary/40' : 'text-on-surface-variant/50'}">
                        ${new Date(m.created_at).toLocaleString('id-ID', {day:'2-digit',month:'short',year:'numeric',hour:'2-digit',minute:'2-digit',hour12:false})}
                    </p>
                </div>
            </div>
        `).join('');
        el.scrollTop = el.scrollHeight;
        document.getElementById('chat-input-area').classList.remove('hidden');
    }

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
            const resp = await fetch(`/komplain/${currentChat.id}/messages`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ pesan })
            });
            if (resp.ok) loadMessages();
        } catch (_) {
            input.value = pesan;
        } finally {
            document.getElementById('chat-send').disabled = false;
        }
    }

    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeChatModal(); });

    document.addEventListener('DOMContentLoaded', () => {
        const scope = document.querySelector('[data-table-scope]');
        if (!scope) return;

        const rows = Array.from(scope.querySelectorAll('[data-table-row]'));
        const chipBtns = document.querySelectorAll('#chip-group .chip-btn');
        const searchInput = document.getElementById('komplain-search');
        const clearBtn = document.getElementById('clear-search');
        const countEl = document.getElementById('result-count');
        const emptySearch = document.getElementById('empty-search');

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
                    row.querySelector('.row-num').textContent = visible;
                }
            });

            countEl.textContent = visible;
            emptySearch.classList.toggle('hidden', visible > 0);
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
