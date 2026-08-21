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
    <!-- Pencarian -->
    <div class="mb-6">
        <div class="relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
            <input
                class="w-full bg-surface-container-low border border-muted-border rounded pl-12 pr-4 py-3 font-body-md text-on-surface focus:outline-none focus:border-primary transition-colors"
                placeholder="Cari pengguna berdasarkan nama atau email..." type="text" />
        </div>
    </div>

    <!-- Filter -->
    <div class="mb-8 overflow-x-auto hide-scrollbar">
        <div class="flex gap-2 whitespace-nowrap pb-2">
            <button class="px-4 py-2 bg-primary text-on-primary font-label-sm text-label-sm uppercase rounded transition-colors">Semua</button>
            <button class="px-4 py-2 bg-surface-container-low text-on-surface border border-muted-border font-label-sm text-label-sm uppercase rounded hover:bg-surface-container transition-colors">Pelanggan</button>
            <button class="px-4 py-2 bg-surface-container-low text-on-surface border border-muted-border font-label-sm text-label-sm uppercase rounded hover:bg-surface-container transition-colors">Pemilik</button>
            <button class="px-4 py-2 bg-surface-container-low text-on-surface border border-muted-border font-label-sm text-label-sm uppercase rounded hover:bg-surface-container transition-colors">Admin</button>
            <button class="px-4 py-2 bg-surface-container-low text-on-surface border border-muted-border font-label-sm text-label-sm uppercase rounded hover:bg-surface-container transition-colors">Aktif</button>
            <button class="px-4 py-2 bg-surface-container-low text-on-surface border border-muted-border font-label-sm text-label-sm uppercase rounded hover:bg-surface-container transition-colors">Non-aktif</button>
        </div>
    </div>

    <!-- Daftar Pengguna -->
    <div class="grid gap-element-gap md:grid-cols-2 xl:grid-cols-3">
        <div class="bg-surface-container-low p-4 rounded border border-muted-border relative cursor-pointer"
            onclick="openUserDetail()">
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

        <div class="bg-surface-container-low p-4 rounded border border-muted-border relative cursor-pointer"
            onclick="openUserDetail()">
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

        <div class="bg-surface-container-low p-4 rounded border border-muted-border relative cursor-pointer"
            onclick="openUserDetail()">
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

    <div class="flex justify-center mt-section-gap mb-8">
        <button class="px-8 py-3 border border-deep-onyx text-deep-onyx font-label-sm text-label-sm uppercase tracking-widest hover:bg-surface-container-low transition-colors">Muat Lebih Banyak</button>
    </div>

    <!-- Overlay Detail Pengguna -->
    <div class="fixed inset-0 bg-scrim/40 z-[60] hidden transition-opacity duration-300 opacity-0 backdrop-blur-sm"
        id="userDetailOverlay" onclick="closeUserDetail()"></div>

    <!-- Bottom Sheet Detail Pengguna -->
    <div class="fixed bottom-0 left-0 right-0 bg-surface z-[70] transform translate-y-full transition-transform duration-300 rounded-t-xl border-t border-muted-border flex flex-col max-h-[85vh]"
        id="userDetailSheet">
        <div class="flex justify-center pt-3 pb-1 w-full shrink-0" onclick="closeUserDetail()">
            <div class="w-12 h-1 bg-outline-variant rounded-full"></div>
        </div>
        <div class="overflow-y-auto px-container-margin pb-safe pt-4">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-16 h-16 rounded-full overflow-hidden bg-surface-container-high border border-muted-border shrink-0">
                    <img class="w-full h-full object-cover"
                        alt="Foto profil Eleanor Vance"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuC-9hgQtZbsQJEdb7AUHOzcjQI9ZHmBK_beG6Aat8pSOYZAg3XOhHijVJ5sfoIrU_Z747EAjvbFhE5iZBT_8ExSVtZTqYnzuYSm7gXmz2iiLAlvLyqPVM2LEqNXb88L-j8N_BeIzqdvW7U0jYLVz1Smf2rLlB91uOMANjMhnEZp7UT6hjUdp-rUJfIfoSc8YwkCHk9z8By-NumM5JfwBZrkYG6i5HYbQxdSDUkqVhAe755rmlmv7fYewg" />
                </div>
                <div>
                    <h2 class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">Eleanor Vance</h2>
                    <p class="font-body-md text-body-md text-on-surface-variant">eleanor.v@example.com</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-surface-container-low p-3 border border-muted-border rounded">
                    <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant block mb-1">Peran</span>
                    <span class="font-title-md text-on-surface">Pemilik</span>
                </div>
                <div class="bg-surface-container-low p-3 border border-muted-border rounded">
                    <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant block mb-1">Status</span>
                    <div class="flex items-center gap-1">
                        <div class="w-2 h-2 rounded-full bg-secondary-container"></div>
                        <span class="font-title-md text-on-surface">Aktif</span>
                    </div>
                </div>
            </div>
            <div class="mb-6">
                <h3 class="font-title-md text-title-md mb-3 border-b border-muted-border pb-2">Toko yang Dimiliki</h3>
                <div class="flex items-center justify-between py-2">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-deep-onyx text-on-primary flex items-center justify-center font-label-sm text-label-sm">
                            LF
                        </div>
                        <span class="font-body-md text-on-surface">Lunara Fashion</span>
                    </div>
                    <span class="material-symbols-outlined text-on-surface-variant text-sm">open_in_new</span>
                </div>
                <div class="flex items-center justify-between py-2 border-t border-muted-border">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-surface-variant text-on-surface flex items-center justify-center font-label-sm text-label-sm">
                            EH
                        </div>
                        <span class="font-body-md text-on-surface">Eleanor Home</span>
                    </div>
                    <span class="material-symbols-outlined text-on-surface-variant text-sm">open_in_new</span>
                </div>
            </div>
            <div class="mb-8">
                <h3 class="font-title-md text-title-md mb-3 border-b border-muted-border pb-2">Aktivitas Terbaru</h3>
                <ul class="space-y-3">
                    <li class="flex gap-3">
                        <span class="material-symbols-outlined text-on-surface-variant text-[20px] mt-0.5">login</span>
                        <div>
                            <p class="font-body-md text-sm text-on-surface">Masuk dari perangkat baru</p>
                            <p class="font-label-sm text-[10px] text-on-surface-variant uppercase mt-1">24 Okt 2023 •
                                09.41</p>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="material-symbols-outlined text-on-surface-variant text-[20px] mt-0.5">storefront</span>
                        <div>
                            <p class="font-body-md text-sm text-on-surface">Memperbarui kebijakan toko "Lunara Fashion"</p>
                            <p class="font-label-sm text-[10px] text-on-surface-variant uppercase mt-1">22 Okt 2023 •
                                14.20</p>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="flex flex-col gap-3 pb-8">
                <button class="w-full py-3 bg-surface border border-muted-border text-on-surface font-label-sm text-label-sm uppercase tracking-widest hover:bg-surface-container-low transition-colors text-center">Ubah Peran</button>
                <button class="w-full py-3 bg-error/10 border border-error/20 text-error font-label-sm text-label-sm uppercase tracking-widest hover:bg-error/20 transition-colors text-center">Nonaktifkan Akun</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openUserDetail() {
        const overlay = document.getElementById('userDetailOverlay');
        const sheet = document.getElementById('userDetailSheet');

        overlay.classList.remove('hidden');
        setTimeout(() => {
            overlay.classList.remove('opacity-0');
            sheet.classList.remove('translate-y-full');
        }, 10);
    }

    function closeUserDetail() {
        const overlay = document.getElementById('userDetailOverlay');
        const sheet = document.getElementById('userDetailSheet');

        overlay.classList.add('opacity-0');
        sheet.classList.add('translate-y-full');

        setTimeout(() => {
            overlay.classList.add('hidden');
        }, 300);
    }
</script>
@endpush
