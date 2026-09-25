@extends('layouts.superadmin')

@section('title', 'Manajemen Toko')

@section('header-title', 'Data Toko')
@section('header-badge', 'Kelola')

@section('header-subtitle', 'Verifikasi, tolak, tangguhkan, dan aktifkan kembali toko penjual.')

@php
    $tabs = [
        'semua' => 'Semua',
        \App\Models\Store::STATUS_PENDING => 'Menunggu',
        \App\Models\Store::STATUS_AKTIF => 'Aktif',
        \App\Models\Store::STATUS_NONAKTIF => 'Ditangguhkan',
        \App\Models\Store::STATUS_DITOLAK => 'Ditolak',
    ];
@endphp

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .material-symbols-outlined.filled { font-variation-settings: 'FILL' 1; }

    .toko-card { transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease; }
    .toko-card:hover .toko-detail-hint { opacity: 1; transform: translateX(0); }
    .toko-detail-hint { opacity: 0; transform: translateX(-6px); transition: all 0.25s ease; }
</style>
@endpush

@section('content')
@include('partials.flash-toast')

<div class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 mb-6">
    <div class="flex items-center gap-2 mb-3 flex-wrap">
        <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
        <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter Status Toko</span>
        <span class="ml-auto flex items-center gap-2">
            <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Tampilan</span>
            <div class="inline-flex bg-surface-container-low border border-muted-border rounded-lg p-1 gap-1">
                <button type="button" data-view="kartu" class="view-mode-btn px-3 py-1.5 rounded-md text-xs font-medium text-on-surface-variant">Kartu</button>
                <button type="button" data-view="tabel" class="view-mode-btn px-3 py-1.5 rounded-md text-xs font-medium text-on-surface-variant">Tabel</button>
            </div>
        </span>
    </div>
    <div id="toko-tabs" class="flex flex-wrap gap-2.5">
        @foreach ($tabs as $key => $label)
            <button type="button" data-status="{{ $key }}" class="toko-filter-btn px-4 py-2 rounded-lg border font-label-sm uppercase tracking-wider transition-colors {{ $activeStatus === $key
                ? 'bg-deep-onyx text-on-primary border-deep-onyx hover:bg-deep-onyx/90'
                : 'bg-surface-container-low text-on-surface-variant border-muted-border hover:bg-surface-container-high hover:text-on-surface hover:border-gold-accent' }}">
                {{ $label }} <span class="opacity-60">({{ $stats[$key] ?? 0 }})</span>
            </button>
        @endforeach
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center gap-3 pt-4">
        <div class="relative flex-1">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
            <input id="toko-search" class="w-full bg-surface-container-low border border-muted-border rounded-lg pl-11 pr-10 py-3 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" type="text" placeholder="Cari nama toko, pemilik, lokasi, atau telepon..." />
            <button type="button" id="clear-search" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-gold-accent opacity-0 transition-opacity">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <p class="text-on-surface-variant font-body-md text-xs shrink-0">
            <span id="result-count">{{ $stores->total() }}</span> toko
        </p>
    </div>
</div>

<section data-table-scope class="px-gutter md:px-container-margin py-8">
    <div id="store-list-holder">
        @include('SuperAdmin.manajemen-toko.partials.store-list')
    </div>
</section>
@endsection

@push('scripts')
<script>
    const statusMeta = {
        aktif: {
            chipLabel: 'Aktif',
            chipClass: 'inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-success/10 text-success border-success/20',
            verification: 'Terverifikasi'
        },
        pending: {
            chipLabel: 'Menunggu Tinjauan',
            chipClass: 'inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-gold-accent/10 text-gold-accent border-gold-accent/30',
            verification: 'Dokumen lengkap • Menunggu review'
        },
        nonaktif: {
            chipLabel: 'Ditangguhkan',
            chipClass: 'inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-error/10 text-error border-error/20',
            verification: 'Ditangguhkan oleh Admin'
        },
        ditolak: {
            chipLabel: 'Ditolak',
            chipClass: 'inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-error/10 text-error border-error/20',
            verification: 'Pengajuan ditolak • Perbaiki lalu ajukan ulang'
        }
    };

    let activeCard = null;
    let activeDocs = [];

    const actionUrls = {
        setujui: (id) => '{{ route('superadmin.manajemen-toko.setujui', ':id:') }}'.replace(':id:', id),
        tolak: (id) => '{{ route('superadmin.manajemen-toko.tolak', ':id:') }}'.replace(':id:', id),
        tangguhkan: (id) => '{{ route('superadmin.manajemen-toko.tangguhkan', ':id:') }}'.replace(':id:', id),
        aktifkan: (id) => '{{ route('superadmin.manajemen-toko.aktifkan', ':id:') }}'.replace(':id:', id),
        dokumenSetujui: (sid, did) => '{{ route('superadmin.manajemen-toko.dokumen.setujui', [':sid:', ':did:']) }}'.replace(':sid:', sid).replace(':did:', did),
        dokumenTolak: (sid, did) => '{{ route('superadmin.manajemen-toko.dokumen.tolak', [':sid:', ':did:']) }}'.replace(':sid:', sid).replace(':did:', did)
    };

    function openStoreModal(card) {
        activeCard = card;
        const d = card.dataset;
        const meta = statusMeta[d.status];

        document.getElementById('store-avatar').textContent = d.initial;
        document.getElementById('modal-title').textContent = d.name;
        const chip = document.getElementById('store-status-chip');
        chip.textContent = meta.chipLabel;
        chip.className = meta.chipClass;
        document.getElementById('store-meta').textContent = 'Bergabung ' + d.joined + ' \u2022 ' + d.location;
        document.getElementById('stat-products').textContent = d.products;
        document.getElementById('stat-orders').textContent = d.orders;
        document.getElementById('stat-rating').textContent = d.rating;
        document.getElementById('store-desc').textContent = d.desc;
        document.getElementById('info-owner').textContent = d.owner;
        document.getElementById('info-location').textContent = d.location;
        document.getElementById('info-joined').textContent = d.joined;
        document.getElementById('info-verification').textContent = meta.verification;
        document.getElementById('info-phone').textContent = d.phone || '-';

        renderStoreDocs(d.id, JSON.parse(d.dokumen || '[]'));

        const reasonBox = document.getElementById('reject-reason-box');
        if (d.status === 'ditolak' && d.reason) {
            reasonBox.classList.remove('hidden');
            document.getElementById('reject-reason-text').textContent = d.reason;
        } else {
            reasonBox.classList.add('hidden');
        }

        const mainBtn = document.getElementById('store-action-main');
        const rejectBtn = document.getElementById('store-action-reject');
        const mainForm = document.getElementById('store-action-form');

        if (d.status === 'pending') {
            mainBtn.textContent = 'Setujui Toko';
            mainBtn.dataset.confirm = 'false';
            mainBtn.classList.remove('hidden');
            mainForm.action = actionUrls.setujui(d.id);
            rejectBtn.classList.remove('hidden');
            document.getElementById('store-action-info')?.classList.remove('hidden');
            document.getElementById('store-action-suspend')?.classList.add('hidden');
        } else if (d.status === 'aktif') {
            mainBtn.classList.add('hidden');
            rejectBtn.classList.add('hidden');
            document.getElementById('store-action-info')?.classList.add('hidden');
            document.getElementById('store-action-suspend')?.classList.add('hidden');
            const suspendBtn = document.getElementById('store-action-suspend');
            suspendBtn.classList.remove('hidden');
            suspendBtn.onclick = () => { closeStoreModal(); openSuspendModal(d.id, d.name); };
        } else if (d.status === 'nonaktif') {
            mainBtn.textContent = 'Aktifkan Kembali';
            mainBtn.dataset.confirm = 'false';
            mainBtn.classList.remove('hidden');
            mainForm.action = actionUrls.aktifkan(d.id);
            rejectBtn.classList.add('hidden');
            document.getElementById('store-action-info')?.classList.add('hidden');
            document.getElementById('store-action-suspend')?.classList.add('hidden');
            meta.verification = d.sampai
                ? 'Ditangguhkan sementara — aktif kembali ' + d.sampai
                : 'Ditangguhkan oleh Admin tanpa batas waktu';
        } else if (d.status === 'ditolak') {
            mainBtn.textContent = 'Pulihkan & Setujui';
            mainBtn.dataset.confirm = 'true';
            mainBtn.classList.remove('hidden');
            mainForm.action = actionUrls.setujui(d.id);
            rejectBtn.classList.add('hidden');
            document.getElementById('store-action-info')?.classList.add('hidden');
            document.getElementById('store-action-suspend')?.classList.add('hidden');
        } else {
            mainBtn.classList.add('hidden');
            rejectBtn.classList.add('hidden');
            document.getElementById('store-action-info')?.classList.add('hidden');
            document.getElementById('store-action-suspend')?.classList.add('hidden');
        }

        document.getElementById('store-modal-scroll').scrollTop = 0;
        const modal = document.getElementById('store-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeStoreModal() {
        const modal = document.getElementById('store-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    function confirmStoreAction() {
        if (document.getElementById('store-action-main').dataset.confirm === 'true') {
            pageConfirm('Pulihkan dan setujui toko yang sebelumnya ditolak ini?', {
                title: 'Setujui Toko',
                sub: 'Toko akan kembali aktif dan dapat menerima pesanan.',
                accent: 'primary',
                yesLabel: 'Ya, Setujui',
                onConfirm: function () {
                    closeStoreModal();
                    document.getElementById('store-action-form').submit();
                }
            });
            return false;
        }
        closeStoreModal();
        return true;
    }

    function openRejectModal() {
        if (!activeCard) return;
        document.getElementById('reject-store-name').textContent = activeCard.dataset.name;
        document.getElementById('reject-form-real').action = actionUrls.tolak(activeCard.dataset.id);
        const textarea = document.getElementById('reject-alasan-input');
        textarea.value = '';
        const modal = document.getElementById('reject-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeRejectModal() {
        const modal = document.getElementById('reject-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    const docMeta = {
        ktp: { label: 'KTP / Identitas Owner', icon: 'description' },
        npwp: { label: 'NPWP Toko', icon: 'receipt_long' },
        foto_depan: { label: 'Foto Depan Toko', icon: 'storefront' },
        siu: { label: 'Surat Izin Usaha (NIB)', icon: 'gavel' }
    };

    function previewUrl(path) {
        return '{{ asset('storage') }}' + '/' + path;
    }

    function renderStoreDocs(storeId, docs = []) {
        const container = document.getElementById('store-docs-list');
        const allBtn = document.getElementById('btn-open-all-docs');
        const order = { ktp: 0, npwp: 1, foto_depan: 2, siu: 3 };
        activeDocs = (docs || []).slice().sort(function (a, b) {
            const ka = order[a.jenis] !== undefined ? order[a.jenis] : 99;
            const kb = order[b.jenis] !== undefined ? order[b.jenis] : 99;
            return ka - kb;
        });
        if (activeDocs.length === 0) {
            container.innerHTML = '<div class="bg-surface-container-low border border-muted-border rounded-lg p-4 text-sm text-on-surface-variant">Belum ada dokumen diunggah.</div>';
            allBtn.classList.add('hidden');
            allBtn.classList.remove('inline-flex');
            return;
        }
        let html = '';
        activeDocs.forEach(function (d) {
            const meta = docMeta[d.jenis] || { label: d.jenis, icon: 'description' };
            const verified = d.status === 'terverifikasi';
            const rejected = d.status === 'ditolak';
            const badgeClass = verified ? 'bg-success/10 text-success border-success/20'
                : (rejected ? 'bg-error/10 text-error border-error/20'
                   : 'bg-surface-container-high text-on-surface-variant border-outline-variant');
            const badgeIcon = verified ? 'check_circle' : (rejected ? 'cancel' : 'schedule');
            const badgeLabel = verified ? 'Terverifikasi' : (rejected ? 'Ditolak' : 'Menunggu');
            const catatan = rejected && d.catatan ? '<p class="text-xs text-on-surface-variant mt-2">' + d.catatan + '</p>' : '';
            const metaLabelSafe = meta.label.replace(/'/g, "\\'");
            const actions = verified ? ''
                : '<div class="flex gap-2 flex-wrap shrink-0">'
                    + '<form method="POST" action="' + actionUrls.dokumenSetujui(storeId, d.id) + '">@csrf<button type="submit" class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider rounded-lg border border-secondary/40 text-secondary hover:bg-secondary/10">Setujui</button></form>'
                    + '<button type="button" onclick="openDocRejectModal(\'' + storeId + '\',' + d.id + ',\'' + metaLabelSafe + '\')" class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider rounded-lg border border-error/40 text-error hover:bg-error/10">Tolak</button>'
                    + '</div>';
            html += '<div class="bg-surface-container-low border border-muted-border rounded-lg p-4 min-w-0">'
                + '<div class="flex items-start gap-3">'
                + '<div class="w-9 h-9 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[18px]">' + meta.icon + '</span></div>'
                + '<div class="min-w-0 flex-1">'
                + '<span class="block text-[10px] font-label-sm text-on-surface-variant uppercase tracking-widest">Dokumen</span>'
                + '<span class="font-title-md text-title-md text-on-surface block truncate">' + meta.label + '</span>'
                + catatan
                + '</div>'
                + '<span class="inline-flex shrink-0 items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border ' + badgeClass + '"><span class="material-symbols-outlined fill text-[12px]">' + badgeIcon + '</span>' + badgeLabel + '</span>'
                + '</div>'
                + '<div class="flex items-center justify-between mt-4 pt-3 border-t border-muted-border gap-2 flex-wrap">'
                + '<a href="' + previewUrl(d.path) + '" target="_blank" rel="noopener" class="text-[11px] font-bold uppercase tracking-wider text-gold-accent inline-flex items-center gap-1 whitespace-nowrap"><span class="material-symbols-outlined text-[14px]">visibility</span>Lihat</a>'
                + actions
                + '</div>'
                + '</div>';
        });
        container.innerHTML = html;
        allBtn.classList.remove('hidden');
        allBtn.classList.add('inline-flex');
    }

    function openAllDocs() {
        if (activeDocs.length === 0) return;
        document.getElementById('all-docs-store-name').textContent = activeCard ? activeCard.dataset.name : '-';
        document.getElementById('all-docs-count').textContent = activeDocs.length;
        const grid = document.getElementById('all-docs-grid');
        grid.innerHTML = '';
        activeDocs.forEach(function (d) {
            const meta = docMeta[d.jenis] || { label: d.jenis, icon: 'description' };
            const verified = d.status === 'terverifikasi';
            const rejected = d.status === 'ditolak';
            const badgeClass = verified ? 'bg-success/10 text-success border-success/20'
                : (rejected ? 'bg-error/10 text-error border-error/20'
                   : 'bg-surface-container-high text-on-surface-variant border-outline-variant');
            const badgeIcon = verified ? 'check_circle' : (rejected ? 'cancel' : 'schedule');
            const badgeLabel = verified ? 'Terverifikasi' : (rejected ? 'Ditolak' : 'Menunggu');
            const missId = 'all-doc-missing-' + d.id;
            const onerr = 'this.style.display=\'none\';document.getElementById(\'' + missId + '\').style.display=\'flex\';';
            const isPdf = /\.pdf$/i.test(d.path);
            const preview = isPdf
                ? '<div class="w-full h-40 bg-surface-container-low border border-muted-border rounded-lg flex flex-col items-center justify-center gap-2">'
                    + '<span class="material-symbols-outlined text-[40px] text-gold-accent">picture_as_pdf</span>'
                    + '<span class="text-[10px] font-label-sm uppercase tracking-widest text-on-surface-variant">Dokumen PDF</span>'
                    + '</div>'
                : '<img class="w-full h-40 object-cover" alt="' + meta.label + '" src="' + previewUrl(d.path) + '" loading="lazy" onerror="' + onerr + '">';
            const fallback = '<div id="' + missId + '" style="display:none" class="w-full h-40 bg-surface-container-low border border-muted-border rounded-lg flex-col items-center justify-center gap-2">'
                + '<span class="material-symbols-outlined text-[40px] text-on-surface-variant">broken_image</span>'
                + '<span class="text-[10px] font-label-sm uppercase tracking-widest text-on-surface-variant">File tidak ditemukan</span>'
                + '</div>';
            grid.innerHTML += '<div class="bg-surface-container-lowest border border-muted-border rounded-xl overflow-hidden flex flex-col">'
                + '<div class="relative">' + preview + fallback + '</div>'
                + '<div class="p-4 flex flex-col gap-2 flex-1">'
                + '<div class="flex items-center justify-between gap-2 flex-wrap">'
                + '<span class="text-[10px] font-label-sm uppercase tracking-widest text-on-surface-variant">' + meta.label + '</span>'
                + '<span class="inline-flex shrink-0 items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold uppercase border ' + badgeClass + '"><span class="material-symbols-outlined fill text-[11px]">' + badgeIcon + '</span>' + badgeLabel + '</span>'
                + '</div>'
                + '<a href="' + previewUrl(d.path) + '" target="_blank" rel="noopener" class="mt-auto inline-flex items-center justify-center gap-1.5 px-3 py-2 text-[10px] font-bold uppercase tracking-wider rounded-lg border border-gold-accent/40 text-gold-accent hover:bg-gold-accent/10 transition-colors"><span class="material-symbols-outlined text-[14px]">open_in_new</span>Buka di Tab Baru</a>'
                + '</div>'
                + '</div>';
        });
        const modal = document.getElementById('all-docs-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeAllDocs() {
        const modal = document.getElementById('all-docs-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        if (document.getElementById('store-modal').classList.contains('hidden')) {
            document.body.style.overflow = '';
        }
    }

    function openDocRejectModal(storeId, docId, label) {
        document.getElementById('doc-reject-name').textContent = label;
        document.getElementById('doc-reject-form').action = actionUrls.dokumenTolak(storeId, docId);
        document.getElementById('doc-reject-alasan-input').value = '';
        const modal = document.getElementById('doc-reject-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDocRejectModal() {
        const modal = document.getElementById('doc-reject-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function openSuspendModal(storeId, storeName) {
        document.getElementById('suspend-store-name').textContent = storeName;
        document.getElementById('suspend-form').action = actionUrls.tangguhkan(storeId);
        const permanen = document.querySelector('input[name="tipe_suspend"][value="permanen"]');
        permanen.checked = true;
        document.getElementById('sampai-input').value = '';
        document.getElementById('sampai-input').disabled = true;
        const modal = document.getElementById('suspend-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeSuspendModal() {
        const modal = document.getElementById('suspend-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    let _confirmCb = null;

    function pageConfirm(message, options) {
        options = options || {};
        document.getElementById('confirm-message').textContent = message;
        document.getElementById('confirm-title').textContent = options.title || 'Konfirmasi';
        document.getElementById('confirm-sub').textContent = options.sub || '';
        const yes = document.getElementById('confirm-yes');
        yes.className = 'btn-modal ' + (options.accent === 'primary' ? 'btn-modal-primary' : 'btn-modal-danger');
        yes.textContent = options.yesLabel || 'Ya, Lanjutkan';
        _confirmCb = options.onConfirm || (function () {});
        const modal = document.getElementById('confirm-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        yes.focus();
    }

    function closeConfirmModal() {
        _confirmCb = null;
        const modal = document.getElementById('confirm-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function resetConfirmModal() {
        _confirmCb = null;
        const modal = document.getElementById('confirm-modal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    resetConfirmModal();

    document.addEventListener('click', function (event) {
        const yes = event.target && event.target.closest ? event.target.closest('#confirm-yes') : null;
        if (! yes) return;
        const cb = _confirmCb;
        closeConfirmModal();
        if (cb) cb();
    });

    function confirmSuspend() {
        const isSementara = document.querySelector('input[name="tipe_suspend"][value="sementara"]').checked;
        if (isSementara) {
            const sampai = document.getElementById('sampai-input').value;
            if (! sampai) {
                showRalivaToast('Mohon pilih tanggal berakhirnya penangguhan.', 'warning');
                return false;
            }
            if (new Date(sampai) <= new Date()) {
                showRalivaToast('Batas waktu harus di masa depan.', 'warning');
                return false;
            }
            const tanggal = new Date(sampai).toLocaleString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' });
            pageConfirm('Toko akan ditangguhkan sementara hingga ' + tanggal + '. Lanjutkan?', {
                title: 'Konfirmasi Penangguhan',
                sub: 'Toko aktif kembali otomatis saat melewati batas waktu tersebut.',
                accent: 'danger',
                yesLabel: 'Ya, Tangguhkan',
                onConfirm: function () {
                    closeSuspendModal();
                    document.getElementById('suspend-form').submit();
                }
            });
            return false;
        }
        pageConfirm('Toko akan ditangguhkan secara permanen. Lanjutkan?', {
            title: 'Konfirmasi Penangguhan',
            sub: 'Hanya Super Admin yang dapat mengaktifkan kembali toko ini.',
            accent: 'danger',
            yesLabel: 'Ya, Tangguhkan',
            onConfirm: function () {
                closeSuspendModal();
                document.getElementById('suspend-form').submit();
            }
        });
        return false;
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') { closeAllDocs(); closeStoreModal(); closeRejectModal(); closeDocRejectModal(); closeSuspendModal(); closeConfirmModal(); }
    });
</script>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const scope = document.querySelector('[data-table-scope]');
    if (!scope) return;

    const searchInput = document.getElementById('toko-search');
    const clearBtn = document.getElementById('clear-search');
    const countEl = document.getElementById('result-count');
    const holder = document.getElementById('store-list-holder');

    const tokoBaseUrl = '{{ route('superadmin.manajemen-toko') }}';

    function rowsNow() {
        return Array.from(scope.querySelectorAll('[data-table-row]'));
    }

    function activeViewContainer() {
        return document.getElementById(storeViewMode === 'tabel' ? 'store-table-view' : 'store-cards-view');
    }

    function visibleStoreCount() {
        const c = activeViewContainer();
        return c ? c.querySelectorAll('[data-table-row]:not(.hidden)').length : 0;
    }

    function applyStoreFilter() {
        const term = searchInput.value.trim().toLowerCase();
        const rows = rowsNow();
        const totalEl = holder.querySelector('[data-store-total]');
        const total = Number(totalEl ? totalEl.getAttribute('data-store-total') : rows.length);
        const emptySearch = holder.querySelector('#toko-empty-search');
        const vis = visibleStoreCount();

        rows.forEach((row) => {
            const show = !term || (row.getAttribute('data-search') || '').includes(term);
            row.classList.toggle('hidden', !show);
        });

        countEl.textContent = term ? visibleStoreCount() : total;
        if (emptySearch) emptySearch.classList.toggle('hidden', (term ? visibleStoreCount() : vis) > 0 || rows.length === 0);
    }

    let debounce;
    searchInput.addEventListener('input', () => {
        clearBtn.classList.toggle('opacity-0', !searchInput.value);
        clearTimeout(debounce);
        debounce = setTimeout(applyStoreFilter, 200);
    });

    clearBtn.addEventListener('click', () => {
        searchInput.value = '';
        clearBtn.classList.add('opacity-0');
        applyStoreFilter();
    });

    // ---- Tampilan Kartu / Tabel ----
    let storeViewMode = localStorage.getItem('sa_toko_view') || 'kartu';

    function syncStoreViews() {
        const cards = document.getElementById('store-cards-view');
        const table = document.getElementById('store-table-view');
        const kosong = document.getElementById('toko-kosong');
        const hasAny = rowsNow().length > 0;

        if (!hasAny) {
            if (kosong) kosong.classList.remove('hidden');
            if (cards) cards.classList.remove('hidden');
            if (table) table.classList.add('hidden');
            return;
        }
        if (kosong) kosong.classList.add('hidden');
        if (cards) cards.classList.toggle('hidden', storeViewMode !== 'kartu');
        if (table) table.classList.toggle('hidden', storeViewMode !== 'tabel');
    }

    function setViewButtonState() {
        document.querySelectorAll('.view-mode-btn').forEach((b) => {
            const active = b.getAttribute('data-view') === storeViewMode;
            b.classList.toggle('bg-deep-onyx', active);
            b.classList.toggle('text-on-primary', active);
            b.classList.toggle('text-on-surface-variant', !active);
        });
    }

    document.querySelectorAll('.view-mode-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            storeViewMode = btn.getAttribute('data-view');
            localStorage.setItem('sa_toko_view', storeViewMode);
            setViewButtonState();
            syncStoreViews();
        });
    });

    // ---- Filter status via AJAX (tanpa refresh) ----
    let currentStoreStatus = '{{ $activeStatus }}';

    function setFilterButtonState() {
        document.querySelectorAll('.toko-filter-btn').forEach((b) => {
            const active = b.getAttribute('data-status') === currentStoreStatus;
            b.classList.toggle('bg-deep-onyx', active);
            b.classList.toggle('text-on-primary', active);
            b.classList.toggle('border-deep-onyx', active);
            b.classList.toggle('bg-surface-container-low', !active);
            b.classList.toggle('text-on-surface-variant', !active);
            b.classList.toggle('border-muted-border', !active);
        });
    }

    async function loadStoreList(url) {
        const u = new URL(url, window.location.origin);
        u.searchParams.set('partial', '1');
        try {
            const res = await fetch(u.toString(), { headers: { 'Accept': 'text/html' } });
            if (!res.ok) throw new Error(res.status);
            holder.innerHTML = await res.text();
            bindStoreListFresh();
            applyStoreFilter();
            syncStoreViews();
        } catch (err) {
            if (window.showRalivaToast) showRalivaToast('Gagal memuat data toko. Silakan coba lagi.', 'error');
        }
    }

    function bindStoreListFresh() {
        holder.querySelectorAll('[data-modal-open]').forEach((btn) => {
            if (btn.dataset.modalBound) return;
            btn.dataset.modalBound = '1';
            btn.addEventListener('click', () => {
                const modal = document.getElementById(btn.getAttribute('data-modal-open'));
                if (modal && window.ralivaOpenModal) window.ralivaOpenModal(modal);
            });
        });
        holder.querySelectorAll('[data-modal]').forEach((m) => {
            if (m.dataset.modalBound) return;
            m.dataset.modalBound = '1';
            m.addEventListener('mousedown', (e) => {
                if (e.target === m && window.ralivaCloseModal) window.ralivaCloseModal(m);
            });
            m.querySelectorAll('[data-modal-close]').forEach((el) => {
                el.addEventListener('click', () => {
                    if (window.ralivaCloseModal) window.ralivaCloseModal(m);
                });
            });
        });
    }

    document.querySelectorAll('.toko-filter-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            currentStoreStatus = btn.getAttribute('data-status');
            setFilterButtonState();
            const url = currentStoreStatus === 'semua'
                ? tokoBaseUrl
                : tokoBaseUrl + '?status=' + encodeURIComponent(currentStoreStatus);
            loadStoreList(url);
        });
    });

    // Pagination: klik halaman berikutnya tidak merefresh halaman
    scope.addEventListener('click', (e) => {
        const a = e.target.closest('nav[role="navigation"] a[href]');
        if (!a || e.target.closest.bind ? a.getAttribute('target') === '_blank' : false) return;
        e.preventDefault();
        loadStoreList(a.href);
    });

    setFilterButtonState();
    setViewButtonState();
    syncStoreViews();
    applyStoreFilter();
});
</script>
@endpush

@push('modals')
<div aria-labelledby="modal-title" aria-modal="true" role="dialog" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 md:p-6 bg-black/50 backdrop-blur-sm" id="store-modal" onclick="if (event.target === this) closeStoreModal()">
    <div class="relative z-10 w-full max-w-3xl h-[min(795px,92vh)] bg-surface-container-lowest rounded-xl border border-muted-border shadow-2xl flex flex-col overflow-hidden">

        <div class="shrink-0 relative border-b border-muted-border">
            <div class="absolute inset-0 bg-gradient-to-r from-gold-accent/15 via-gold-accent/5 to-transparent pointer-events-none"></div>
            <div class="relative flex items-start justify-between gap-4 p-6 md:p-7">
                <div class="flex items-center gap-5 min-w-0">
                    <div id="store-avatar" class="w-16 h-16 rounded-2xl bg-surface-container-high border-2 border-gold-accent/40 shadow-lg flex items-center justify-center font-title-md text-lg text-on-surface shrink-0">NS</div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h2 class="font-display-lg text-headline-lg-mobile md:text-headline-lg truncate" id="modal-title">Nama Toko</h2>
                        </div>
                        <div class="flex items-center gap-3 mt-1.5 flex-wrap">
                            <span id="store-status-chip" class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-gold-accent/10 text-gold-accent border-gold-accent/30">Menunggu Tinjauan</span>
                            <span id="store-meta" class="text-xs text-on-surface-variant">Bergabung - • -</span>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="closeStoreModal()" class="text-on-surface-variant hover:text-on-surface transition-colors p-2 -mr-2 shrink-0"><span class="material-symbols-outlined">close</span></button>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-6 md:p-8 space-y-8" id="store-modal-scroll">
            <div id="reject-reason-box" class="hidden bg-error/5 border border-error/25 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-error text-[18px] mt-0.5">gpp_bad</span>
                    <div>
                        <p class="font-label-sm text-[10px] uppercase tracking-widest text-error mb-1">Alasan Penolakan</p>
                        <p id="reject-reason-text" class="text-sm text-on-surface"></p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-gutter">
                <div class="relative overflow-hidden bg-surface-container-low border border-muted-border rounded-lg p-4 text-center">
                    <span class="block font-headline-lg-mobile text-headline-lg-mobile text-on-surface" id="stat-products">-</span>
                    <span class="block text-[9px] font-label-sm text-on-surface-variant uppercase tracking-widest mt-1">Produk Aktif</span>
                    <span class="material-symbols-outlined absolute -right-2 -bottom-3 text-[44px] text-gold-accent/15 fill pointer-events-none select-none">checkroom</span>
                </div>
                <div class="relative overflow-hidden bg-surface-container-low border border-muted-border rounded-lg p-4 text-center">
                    <span class="block font-headline-lg-mobile text-headline-lg-mobile text-on-surface" id="stat-orders">-</span>
                    <span class="block text-[9px] font-label-sm text-on-surface-variant uppercase tracking-widest mt-1">Total Pesanan</span>
                    <span class="material-symbols-outlined absolute -right-2 -bottom-3 text-[44px] text-gold-accent/15 fill pointer-events-none select-none">shopping_bag</span>
                </div>
                <div class="relative overflow-hidden bg-surface-container-low border border-muted-border rounded-lg p-4 text-center">
                    <span class="block font-headline-lg-mobile text-headline-lg-mobile text-on-surface flex items-center justify-center gap-1"><span id="stat-rating">-</span><span class="material-symbols-outlined text-[18px] filled text-secondary">star</span></span>
                    <span class="block text-[9px] font-label-sm text-on-surface-variant uppercase tracking-widest mt-1">Rating Toko</span>
                    <span class="material-symbols-outlined absolute -right-2 -bottom-3 text-[44px] text-gold-accent/15 fill pointer-events-none select-none">reviews</span>
                </div>
            </div>

            <section>
                <h4 class="font-title-md text-title-md mb-3 uppercase tracking-wider text-on-surface premium-heading">Deskripsi Toko</h4>
                <p id="store-desc" class="font-body-md text-body-md text-on-surface-variant leading-relaxed max-w-2xl">-</p>
            </section>

            <section>
                <h4 class="font-title-md text-title-md mb-4 uppercase tracking-wider text-on-surface premium-heading">Informasi Toko</h4>
                <div class="grid sm:grid-cols-2 gap-gutter">
                    <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[18px]">person</span></div>
                        <div class="min-w-0"><span class="block text-[10px] font-label-sm text-on-surface-variant uppercase tracking-widest">Pemilik</span><span id="info-owner" class="font-title-md text-title-md text-on-surface block truncate">-</span></div>
                    </div>
                    <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[18px]">calendar_month</span></div>
                        <div class="min-w-0"><span class="block text-[10px] font-label-sm text-on-surface-variant uppercase tracking-widest">Bergabung</span><span id="info-joined" class="font-title-md text-title-md text-on-surface block truncate">-</span></div>
                    </div>
                    <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[18px]">place</span></div>
                        <div class="min-w-0"><span class="block text-[10px] font-label-sm text-on-surface-variant uppercase tracking-widest">Lokasi</span><span id="info-location" class="font-title-md text-title-md text-on-surface block truncate">-</span></div>
                    </div>
                    <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[18px]">fact_check</span></div>
                        <div class="min-w-0"><span class="block text-[10px] font-label-sm text-on-surface-variant uppercase tracking-widest">Verifikasi</span><span id="info-verification" class="font-title-md text-title-md text-on-surface block truncate">-</span></div>
                    </div>
                    <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[18px]">call</span></div>
                        <div class="min-w-0"><span class="block text-[10px] font-label-sm text-on-surface-variant uppercase tracking-widest">Telepon</span><span id="info-phone" class="font-title-md text-title-md text-on-surface block truncate">-</span></div>
                    </div>
                </div>
            </section>

            <section>
                <div class="flex items-center justify-between mb-4 gap-3 flex-wrap">
                    <h4 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading min-w-0">Dokumen Toko</h4>
                    <button id="btn-open-all-docs" type="button" onclick="openAllDocs()" class="hidden items-center gap-1.5 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider rounded-lg border border-gold-accent/40 text-gold-accent hover:bg-gold-accent/10 transition-colors whitespace-nowrap">
                        <span class="material-symbols-outlined text-[14px]">folder_open</span>Lihat Semua Sertifikat
                    </button>
                </div>
                <div id="store-docs-list" class="grid sm:grid-cols-2 gap-gutter">
                    <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 text-sm text-on-surface-variant">Belum ada dokumen.</div>
                </div>
            </section>
        </div>

        <div class="shrink-0 border-t border-muted-border bg-surface/95 backdrop-blur px-6 py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="space-y-1">
                <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant inline-flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[14px] text-gold-accent">history</span>
                    Keputusan tercatat di riwayat aktivitas
                </p>
                <p id="store-action-info" class="text-[11px] text-on-surface-variant hidden">Menyetujui akan otomatis verifikasi dokumen pending & beri 5 slot awal bila kosong.</p>
            </div>
            <div class="flex gap-3 w-full sm:w-auto">
                <button id="store-action-suspend" type="button" class="hidden flex-1 sm:flex-none px-6 py-3 border border-error/40 text-error font-label-sm text-label-sm uppercase tracking-wider rounded-lg hover:bg-error/10 transition-colors">Tangguhkan</button>
                <button id="store-action-reject" type="button" onclick="openRejectModal()" class="flex-1 sm:flex-none px-6 py-3 border border-error/40 text-error font-label-sm text-label-sm uppercase tracking-wider rounded-lg hover:bg-error/10 transition-colors">Tolak</button>
                <form id="store-action-form" method="POST" action="" onsubmit="return confirmStoreAction()">
                    @csrf
                    <button id="store-action-main" type="submit" class="w-full sm:w-auto px-8 py-3 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-wider rounded-lg btn-premium">Setujui Toko</button>
                </form>
            </div>
        </div>
    </div>
</div>

<form id="reject-form-real" method="POST" action="" onsubmit="closeRejectModal(); closeStoreModal()">
    @csrf
    @component('SuperAdmin.partials.premium-confirm', [
        'id' => 'reject-modal',
        'icon' => 'gpp_bad',
        'zIndex' => 110,
        'close' => 'closeRejectModal',
        'dataModal' => true,
    ])
        <div class="p-6">
            <h3 class="font-display-lg text-headline-lg-mobile text-center mb-2" id="reject-title">Tolak Toko</h3>
            <p class="text-on-surface-variant text-sm text-center mb-6">Berikan alasan penolakan untuk <span id="reject-store-name" class="font-bold text-on-surface">-</span>. Pesan ini akan dikirim ke pemilik toko.</p>
            <textarea required minlength="10" maxlength="1000" name="alasan" id="reject-alasan-input" class="w-full border border-muted-border bg-surface-container-low rounded-lg p-3 font-body-md text-sm focus:outline-none focus:border-error focus:ring-1 focus:ring-error mb-6 min-h-[120px] resize-none" placeholder="Misal: Dokumen izin usaha belum lengkap... (minimal 10 karakter)"></textarea>
        </div>
        @slot('footer')
            <div class="flex justify-end gap-3">
                <button type="button" class="btn-modal btn-modal-ghost" onclick="closeRejectModal()">Batal</button>
                <button type="submit" class="btn-modal btn-modal-danger">Konfirmasi Penolakan</button>
            </div>
        @endslot
    @endcomponent
</form>

<form id="doc-reject-form" method="POST" action="" onsubmit="closeDocRejectModal(); closeStoreModal()">
    @csrf
    @component('SuperAdmin.partials.premium-confirm', [
        'id' => 'doc-reject-modal',
        'icon' => 'gpp_bad',
        'zIndex' => 120,
        'close' => 'closeDocRejectModal',
        'dataModal' => true,
    ])
        <div class="p-6">
            <h3 class="font-display-lg text-headline-lg-mobile text-center mb-2" id="doc-reject-title">Tolak Dokumen</h3>
            <p class="text-on-surface-variant text-sm text-center mb-6">Berikan alasan penolakan untuk <span id="doc-reject-name" class="font-bold text-on-surface">-</span>. Penolakan dokumen akan mengubah status pengajuan toko menjadi <span class="font-bold text-error">Ditolak</span>. Pesan ini akan dikirim ke pemilik toko.</p>
            <textarea required minlength="3" maxlength="1000" name="alasan" id="doc-reject-alasan-input" class="w-full border border-muted-border bg-surface-container-low rounded-lg p-3 font-body-md text-sm focus:outline-none focus:border-error focus:ring-1 focus:ring-error mb-6 min-h-[120px] resize-none" placeholder="Alasan penolakan dokumen... (minimal 3 karakter)"></textarea>
        </div>
        @slot('footer')
            <div class="flex justify-end gap-3">
                <button type="button" class="btn-modal btn-modal-ghost" onclick="closeDocRejectModal()">Batal</button>
                <button type="submit" class="btn-modal btn-modal-danger">Konfirmasi Penolakan</button>
            </div>
        @endslot
    @endcomponent
</form>

<div aria-labelledby="all-docs-title" aria-modal="true" role="dialog" class="fixed inset-0 z-[115] hidden items-center justify-center p-4 md:p-6 bg-black/50 backdrop-blur-sm" id="all-docs-modal" onclick="if (event.target === this) closeAllDocs()">
    <div class="relative z-10 w-full max-w-4xl h-[min(760px,92vh)] bg-surface-container-lowest rounded-xl border border-muted-border shadow-2xl flex flex-col overflow-hidden">
        <div class="shrink-0 border-b border-muted-border px-6 md:px-8 py-5">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="font-label-sm text-[10px] uppercase tracking-widest text-gold-accent inline-flex items-center gap-1.5 mb-1"><span class="material-symbols-outlined text-[14px]">folder_open</span>Dokumen Toko</p>
                    <h3 class="font-display-lg text-headline-lg-mobile truncate" id="all-docs-title">Semua Sertifikat</h3>
                    <p class="text-xs text-on-surface-variant mt-0.5"><span id="all-docs-store-name" class="font-bold text-on-surface">-</span> &bull; <span id="all-docs-count">0</span> dokumen</p>
                </div>
                <button type="button" onclick="closeAllDocs()" class="text-on-surface-variant hover:text-on-surface transition-colors p-2 -mr-2 shrink-0"><span class="material-symbols-outlined">close</span></button>
            </div>
        </div>
        <div class="flex-1 overflow-y-auto p-6 md:p-8">
            <div id="all-docs-grid" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-gutter"></div>
        </div>
    </div>
</div>

<form id="suspend-form" method="POST" action="" onsubmit="return confirmSuspend()">
    @csrf
    @component('SuperAdmin.partials.premium-confirm', [
        'id' => 'suspend-modal',
        'icon' => 'block',
        'zIndex' => 105,
        'close' => 'closeSuspendModal',
        'dataModal' => true,
    ])
        <div class="p-6">
            <h3 class="font-display-lg text-headline-lg-mobile text-center mb-2" id="suspend-title">Tangguhkan Toko</h3>
            <p class="text-on-surface-variant text-sm text-center mb-6">Toko <span id="suspend-store-name" class="font-bold text-on-surface">-</span> akan ditangguhkan. Pilih jenis penangguhan:</p>

            <div class="space-y-3 mb-6">
                <label class="flex items-start gap-3 p-3 border border-muted-border rounded-lg cursor-pointer hover:border-gold-accent/50 transition-colors has-[:checked]:border-error has-[:checked]:bg-error/5">
                    <input type="radio" name="tipe_suspend" value="permanen" checked class="mt-0.5 accent-error" />
                    <div>
                        <p class="font-label-sm text-label-sm text-on-surface font-bold">Permanen</p>
                        <p class="text-xs text-on-surface-variant mt-0.5">Toko akan ditangguhkan tanpa batas waktu. Hanya bisa diaktifkan kembali oleh Super Admin.</p>
                    </div>
                </label>
                <label class="flex items-start gap-3 p-3 border border-muted-border rounded-lg cursor-pointer hover:border-gold-accent/50 transition-colors has-[:checked]:border-error has-[:checked]:bg-error/5">
                    <input type="radio" name="tipe_suspend" value="sementara" class="mt-0.5 accent-error" onchange="document.getElementById('sampai-input').disabled = this.value !== 'sementara'; if(this.value !== 'sementara') document.getElementById('sampai-input').value = '';" />
                    <div class="flex-1">
                        <p class="font-label-sm text-label-sm text-on-surface font-bold">Sementara (Berbatas Waktu)</p>
                        <p class="text-xs text-on-surface-variant mt-0.5 mb-3">Toko akan otomatis aktif kembali melewati batas waktu yang ditentukan.</p>
                        <input type="datetime-local" id="sampai-input" name="sampai" disabled class="w-full bg-surface-container-low border border-muted-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-error focus:ring-1 focus:ring-error disabled:opacity-40" />
                    </div>
                </label>
            </div>

        </div>
        @slot('footer')
            <div class="flex justify-end gap-3">
                <button type="button" class="btn-modal btn-modal-ghost" onclick="closeSuspendModal()">Batal</button>
                <button type="submit" class="btn-modal btn-modal-danger">Konfirmasi Tangguhkan</button>
            </div>
        @endslot
    @endcomponent
</form>

@component('SuperAdmin.partials.premium-confirm', [
    'id' => 'confirm-modal',
    'icon' => 'help',
    'iconBox' => 'bg-gold-accent/20 border-gold-accent/30',
    'iconColor' => 'text-gold-accent',
    'zIndex' => 110,
    'close' => 'closeConfirmModal',
    'dataModal' => true,
])
    <div class="p-6 text-center">
        <h3 id="confirm-title" class="font-display-lg text-headline-lg-mobile text-on-surface">Konfirmasi</h3>
        <p id="confirm-sub" class="text-xs text-on-surface-variant mt-1"></p>
        <p id="confirm-message" class="text-sm text-on-surface font-semibold mt-4"></p>
    </div>
    @slot('footer')
        <div class="flex justify-end gap-3">
            <button type="button" class="btn-modal btn-modal-ghost" onclick="closeConfirmModal()">Batal</button>
            <button type="button" id="confirm-yes" class="btn-modal btn-modal-danger">Ya, Lanjutkan</button>
        </div>
    @endslot
@endcomponent

@endpush
