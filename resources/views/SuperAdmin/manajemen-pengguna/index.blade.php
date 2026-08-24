@extends('layouts.superadmin')

@section('title', 'Manajemen Pengguna')

@section('header-title', 'Manajemen Pengguna')
@section('header-badge', 'Kelola')

@section('header-subtitle', 'Kelola pengguna terdaftar di platform.')

@push('styles')
<style>
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endpush

@section('content')
    <!-- Pencarian & Filter -->
    <div class="mb-6 bg-surface-container-lowest border border-muted-border rounded-lg p-4 md:p-5 card-premium flex flex-col gap-4" data-reveal>
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
                <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter Pengguna</span>
            </div>
            <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant/70 hidden sm:inline">Role & Status</span>
        </div>
        <div class="h-px bg-muted-border"></div>
        <div class="relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
            <input
                id="user-search"
                class="w-full bg-surface-container-low border border-muted-border rounded-lg pl-12 pr-4 py-3 font-body-md text-sm text-on-surface placeholder:text-on-surface-variant/60 focus:outline-none focus:border-gold-accent transition-colors"
                placeholder="Cari pengguna berdasarkan nama atau email..." type="text" />
        </div>
        <div class="overflow-x-auto hide-scrollbar -mx-1 px-1">
            <div class="flex gap-2 whitespace-nowrap pb-1">
                <button type="button" data-user-filter="semua" data-filter-key="role" class="px-4 py-2 rounded-lg bg-deep-onyx text-on-primary border border-deep-onyx font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200 shrink-0 shadow-[0_4px_14px_rgba(0,0,0,0.18)]">Semua</button>
                <button type="button" data-user-filter="pelanggan" data-filter-key="role" class="px-4 py-2 rounded-lg bg-surface-container-low text-on-surface-variant border border-muted-border hover:text-on-surface hover:border-gold-accent/50 font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200 shrink-0">Pelanggan</button>
                <button type="button" data-user-filter="pemilik" data-filter-key="role" class="px-4 py-2 rounded-lg bg-surface-container-low text-on-surface-variant border border-muted-border hover:text-on-surface hover:border-gold-accent/50 font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200 shrink-0">Pemilik</button>
                <button type="button" data-user-filter="admin" data-filter-key="role" class="px-4 py-2 rounded-lg bg-surface-container-low text-on-surface-variant border border-muted-border hover:text-on-surface hover:border-gold-accent/50 font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200 shrink-0">Admin</button>
                <button type="button" data-user-filter="aktif" data-filter-key="status" class="px-4 py-2 rounded-lg bg-surface-container-low text-on-surface-variant border border-muted-border hover:text-on-surface hover:border-gold-accent/50 font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200 shrink-0">Aktif</button>
                <button type="button" data-user-filter="non-aktif" data-filter-key="status" class="px-4 py-2 rounded-lg bg-surface-container-low text-on-surface-variant border border-muted-border hover:text-on-surface hover:border-gold-accent/50 font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200 shrink-0">Non-aktif</button>
            </div>
        </div>
    </div>

    <!-- Daftar Pengguna -->
    <div class="grid gap-element-gap md:grid-cols-2 xl:grid-cols-3">
        <div data-user-card data-role="pemilik" data-status="aktif" data-name="Eleanor Vance" data-email="eleanor.v@example.com" data-initial="EV" data-role-label="Pemilik" class="bg-surface-container-low p-4 rounded border border-muted-border relative cursor-pointer card-premium"
            onclick="openUserDetail(this)">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full overflow-hidden bg-surface-container-high border border-muted-border">
                        <img class="w-full h-full object-cover"
                            alt="Foto profil Eleanor Vance"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuCqx-xulJ7pkm8q6unZz3z9E5L3Mek9tQ-qX6EKC-b3owMZ_2PmkSk6YKmkKWjBPtHAKr1pCX3AMCv2uqiJnlCdWYtWnaIgL3bITzuwd4D15HT5S7is-BYMJO0U1lYiDfwnEx2ox0EPCphcAKGQA2aY4-H1nMvQV4_cL6tfGyfeNLzFm3w5ooxDhG2yMzhWU92lTll0266xX-cBn52Dztu9NrNGi518WvO8f3OaiF8q7WdMqxb3rL43Sw" />
                    </div>
                    <div>
                        <h3 class="font-title-md text-title-md text-on-surface">Eleanor Vance</h3>
                        <p class="font-body-md text-body-md text-on-surface-variant text-sm">eleanor.v@example.com</p>
                    </div>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant">chevron_right</span>
            </div>
            <div class="flex items-center justify-between mt-2 pt-3 border-t border-muted-border">
                <div class="flex gap-2">
                    <span class="px-2 py-1 bg-surface-variant text-on-surface font-label-sm text-label-sm uppercase rounded">Pemilik</span>
                </div>
                <div class="flex items-center gap-1 text-sm">
                    <div class="w-2 h-2 rounded-full bg-secondary-container"></div>
                    <span class="text-on-surface-variant font-label-sm text-[10px] uppercase tracking-wider">Aktif</span>
                </div>
            </div>
        </div>

        <div data-user-card data-role="pelanggan" data-status="non-aktif" data-name="Marcus James" data-email="mjames@studio.co" data-initial="MJ" data-role-label="Pelanggan" class="bg-surface-container-low p-4 rounded border border-muted-border relative cursor-pointer card-premium"
            onclick="openUserDetail(this)">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full overflow-hidden bg-surface-container-high border border-muted-border flex items-center justify-center text-on-surface-variant font-title-md">
                        MJ
                    </div>
                    <div>
                        <h3 class="font-title-md text-title-md text-on-surface">Marcus James</h3>
                        <p class="font-body-md text-body-md text-on-surface-variant text-sm">mjames@studio.co</p>
                    </div>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant">chevron_right</span>
            </div>
            <div class="flex items-center justify-between mt-2 pt-3 border-t border-muted-border">
                <div class="flex gap-2">
                    <span class="px-2 py-1 bg-surface-variant text-on-surface font-label-sm text-label-sm uppercase rounded">Pelanggan</span>
                </div>
                <div class="flex items-center gap-1 text-sm">
                    <div class="w-2 h-2 rounded-full bg-outline-variant"></div>
                    <span class="text-on-surface-variant font-label-sm text-[10px] uppercase tracking-wider">Non-aktif</span>
                </div>
            </div>
        </div>

        <div data-user-card data-role="admin" data-status="aktif" data-name="David Chen" data-email="david.c@raliva.com" data-initial="DC" data-role-label="Admin" class="bg-surface-container-low p-4 rounded border border-muted-border relative cursor-pointer card-premium"
            onclick="openUserDetail(this)">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full overflow-hidden bg-surface-container-high border border-muted-border">
                        <img class="w-full h-full object-cover"
                            alt="Foto profil David Chen"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuA8LkiXGG2IJ7mCqgOOMMqZLeUEntp9kWyket1E3IZZQMa44VGX1CmvoV2UrUoZo7Tb85xJrBagh4yvm7TDrHHhr__loOEjPbzhW785GLPGK-Tr34Ljk0UQeIya8iJ3-M6SOddD2ODLsxnkCwrjdLG6B_C6Xy8hfIqnuzeQ57mmoZCvjhD7RUhzRISgvH74axFQNQoAm4y_vAH-tMFquxdq0Ik4Sj4SGzOdIUvrGhljLMg0tzuyz8BTkg" />
                    </div>
                    <div>
                        <h3 class="font-title-md text-title-md text-on-surface">David Chen</h3>
                        <p class="font-body-md text-body-md text-on-surface-variant text-sm">david.c@raliva.com</p>
                    </div>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant">chevron_right</span>
            </div>
            <div class="flex items-center justify-between mt-2 pt-3 border-t border-muted-border">
                <div class="flex gap-2">
                    <span class="px-2 py-1 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase rounded">Admin</span>
                </div>
                <div class="flex items-center gap-1 text-sm">
                    <div class="w-2 h-2 rounded-full bg-secondary-container"></div>
                    <span class="text-on-surface-variant font-label-sm text-[10px] uppercase tracking-wider">Aktif</span>
                </div>
            </div>
        </div>
    </div>

    <p id="user-kosong" class="hidden text-center text-on-surface-variant font-body-md text-sm py-12">Tidak ada pengguna yang sesuai dengan pencarian atau filter.</p>

    <div class="flex justify-center mt-section-gap mb-8">
        <button type="button" onclick="showRalivaToast('Halaman demo: tidak ada pengguna lain untuk dimuat.', 'info')" class="px-8 py-3 border border-deep-onyx text-deep-onyx font-label-sm text-label-sm uppercase tracking-widest hover:bg-surface-container-low transition-colors">Muat Lebih Banyak</button>
    </div>

    <!-- Drawer Detail Pengguna -->
    <div id="userDetailOverlay" class="fixed inset-0 bg-black/50 z-[70] hidden opacity-0 transition-opacity duration-300" onclick="closeUserDetail()"></div>

    <div id="userDetailSheet" class="fixed inset-y-0 right-0 z-[80] w-full max-w-md bg-surface-container-lowest border-l border-muted-border shadow-xl translate-x-full transition-transform duration-300 ease-in-out flex flex-col">
        <div class="shrink-0 relative border-b border-muted-border overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-gold-accent/15 via-gold-accent/5 to-transparent pointer-events-none"></div>
            <div class="relative p-6 pt-8">
                <button type="button" onclick="closeUserDetail()" class="absolute top-4 right-4 text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
                <div id="drawer-initial" class="w-16 h-16 rounded-2xl ring-2 ring-gold-accent/30 bg-surface-container-high border border-muted-border flex items-center justify-center font-title-md text-on-surface mb-3 shadow-sm">EV</div>
                <h2 id="drawer-name" class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface leading-tight pr-10">Eleanor Vance</h2>
                <p id="drawer-email" class="font-body-md text-sm text-on-surface-variant mt-0.5 break-all">eleanor.v@example.com</p>
                <div class="flex gap-2 mt-3 flex-wrap">
                    <span id="drawer-role" class="inline-flex items-center px-2.5 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">Pemilik</span>
                    <span id="drawer-status" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20"><span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>Aktif</span>
                </div>
            </div>
        </div>

        <div id="drawer-scroll" class="flex-1 overflow-y-auto px-6 py-6 space-y-6">
            <section id="drawer-shops-section">
                <h3 class="font-title-md text-title-md mb-3 border-b border-muted-border pb-2 uppercase tracking-wider text-on-surface">Toko yang Dimiliki</h3>
                <div class="flex items-center justify-between py-2">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-deep-onyx text-on-primary flex items-center justify-center font-label-sm text-label-sm shrink-0">LF</div>
                        <div><p class="font-body-md text-on-surface">Lunara Fashion</p><p class="text-xs text-on-surface-variant">124 produk • Rating 4.9</p></div>
                    </div>
                    <span class="material-symbols-outlined text-on-surface-variant text-sm">open_in_new</span>
                </div>
                <div class="flex items-center justify-between py-2 border-t border-muted-border">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-surface-container-high text-on-surface flex items-center justify-center font-label-sm text-label-sm shrink-0">EH</div>
                        <div><p class="font-body-md text-on-surface">Eleanor Home</p><p class="text-xs text-on-surface-variant">32 produk • Rating 4.7</p></div>
                    </div>
                    <span class="material-symbols-outlined text-on-surface-variant text-sm">open_in_new</span>
                </div>
            </section>

            <section id="drawer-no-shops" class="hidden text-center py-6 border border-dashed border-muted-border rounded-lg">
                <span class="material-symbols-outlined text-on-surface-variant text-[28px]">storefront</span>
                <p class="text-on-surface-variant font-body-md text-sm mt-1">Belum memiliki toko.</p>
            </section>

            <section>
                <h3 class="font-title-md text-title-md mb-3 border-b border-muted-border pb-2 uppercase tracking-wider text-on-surface">Aktivitas Terbaru</h3>
                <ul class="space-y-3">
                    <li class="flex gap-3">
                        <span class="material-symbols-outlined text-on-surface-variant text-[20px] mt-0.5">login</span>
                        <div>
                            <p class="font-body-md text-sm text-on-surface">Masuk dari perangkat baru</p>
                            <p class="font-label-sm text-[10px] text-on-surface-variant uppercase mt-1">24 Okt 2023 • 09.41</p>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="material-symbols-outlined text-on-surface-variant text-[20px] mt-0.5">storefront</span>
                        <div>
                            <p class="font-body-md text-sm text-on-surface">Memperbarui kebijakan toko "Lunara Fashion"</p>
                            <p class="font-label-sm text-[10px] text-on-surface-variant uppercase mt-1">22 Okt 2023 • 14.20</p>
                        </div>
                    </li>
                </ul>
            </section>
        </div>

        <div class="shrink-0 border-t border-muted-border p-4 bg-surface-container-lowest flex gap-3">
            <button type="button" onclick="showRalivaToast('Formulir ubah peran hanya dapat diakses Super Admin.', 'manage_accounts')" class="flex-1 py-3 bg-transparent border border-muted-border text-on-surface font-label-sm text-[11px] uppercase tracking-widest hover:bg-surface-container-low hover:border-gold-accent transition-colors text-center rounded-lg">Ubah Peran</button>
            <button type="button" onclick="disableActiveUser()" class="flex-1 py-3 bg-error/10 border border-error/25 text-error font-label-sm text-[11px] uppercase tracking-widest hover:bg-error/20 transition-colors text-center rounded-lg">Nonaktifkan</button>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let activeUserCard = null;

    function openUserDetail(card) {
        activeUserCard = card;
        const d = card.dataset;
        document.getElementById('drawer-initial').textContent = d.initial || '';
        document.getElementById('drawer-name').textContent = d.name || '';
        document.getElementById('drawer-email').textContent = d.email || '';
        document.getElementById('drawer-role').textContent = d.roleLabel || '';
        const statusEl = document.getElementById('drawer-status');
        if (d.status === 'aktif') {
            statusEl.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20';
            statusEl.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>Aktif';
        } else {
            statusEl.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20';
            statusEl.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-error"></span>Non-aktif';
        }
        const isPemilik = d.role === 'pemilik';
        document.getElementById('drawer-shops-section').classList.toggle('hidden', !isPemilik);
        document.getElementById('drawer-no-shops').classList.toggle('hidden', isPemilik);
        const scroller = document.getElementById('drawer-scroll');
        if (scroller) scroller.scrollTop = 0;

        const overlay = document.getElementById('userDetailOverlay');
        const sheet = document.getElementById('userDetailSheet');
        overlay.classList.remove('hidden');
        setTimeout(() => {
            overlay.classList.remove('opacity-0');
            sheet.classList.remove('translate-x-full');
        }, 10);
    }

    function closeUserDetail() {
        const overlay = document.getElementById('userDetailOverlay');
        const sheet = document.getElementById('userDetailSheet');
        overlay.classList.add('opacity-0');
        sheet.classList.add('translate-x-full');
        setTimeout(() => {
            overlay.classList.add('hidden');
        }, 300);
    }

    function disableActiveUser() {
        if (!activeUserCard) return;
        showRalivaToast('Akun ' + activeUserCard.dataset.name + ' dinonaktifkan.', 'block');
        activeUserCard.dataset.status = 'non-aktif';
        closeUserDetail();
    }

    const userFilters = { role: 'semua', status: 'semua' };
    const userCards = document.querySelectorAll('[data-user-card]');
    const userSearch = document.getElementById('user-search');

    const applyUserFilter = () => {
        let visible = 0;
        userCards.forEach((card) => {
            const matchRole = userFilters.role === 'semua' || card.getAttribute('data-role') === userFilters.role;
            const matchStatus = userFilters.status === 'semua' || card.getAttribute('data-status') === userFilters.status;
            const matchSearch = !userSearch || userSearch.value.trim() === '' ||
                card.textContent.toLowerCase().includes(userSearch.value.trim().toLowerCase());
            const show = matchRole && matchStatus && matchSearch;
            card.classList.toggle('hidden', !show);
            if (show) visible++;
        });
        document.getElementById('user-kosong')?.classList.toggle('hidden', visible > 0);
    };

    document.querySelectorAll('[data-user-filter]').forEach((chip) => {
        chip.addEventListener('click', () => {
            const key = chip.getAttribute('data-filter-key');
            const value = chip.getAttribute('data-user-filter');
            const alreadyActive = chip.classList.contains('bg-deep-onyx');
            const activeClasses = ['bg-deep-onyx', 'text-on-primary', 'border-deep-onyx', 'shadow-[0_4px_14px_rgba(0,0,0,0.18)]'];
            const idleClasses = ['bg-surface-container-low', 'text-on-surface-variant', 'border-muted-border', 'hover:text-on-surface', 'hover:border-gold-accent/50'];
            document.querySelectorAll('[data-user-filter][data-filter-key="' + key + '"]').forEach((c) => {
                c.classList.remove(...activeClasses);
                c.classList.add(...idleClasses);
            });
            if (alreadyActive && key === 'status') {
                userFilters[key] = 'semua';
            } else {
                chip.classList.remove(...idleClasses);
                chip.classList.add(...activeClasses);
                userFilters[key] = value;
            }
            applyUserFilter();
        });
    });

    userSearch?.addEventListener('input', applyUserFilter);
</script>
@endpush
