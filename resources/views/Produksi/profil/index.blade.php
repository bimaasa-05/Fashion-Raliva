it @extends('layouts.produksi')

@section('title', 'Profil')

@section('header-title', 'Profil')
@section('header-subtitle', 'Informasi akun dan penugasan produksi Anda.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="h-48 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-section-gap">
        <div class="h-72 bg-surface-container-high rounded-lg animate-pulse"></div>
        <div class="h-72 bg-surface-container-high rounded-lg animate-pulse"></div>
    </div>
</div>

@php
        $puser = Auth::user();
        $pnama = $puser?->nama_lengkap ?? 'Produksi';
        $pw = preg_split('/\s+/', trim($pnama));
        $pi = '';
        if(!empty($pw[0])) $pi .= mb_substr($pw[0],0,1);
        if(isset($pw[1])) $pi .= mb_substr($pw[1],0,1);
        elseif(mb_strlen($pw[0]??'')>1) $pi .= mb_substr($pw[0],1,1);
        $pinit = strtoupper(mb_substr($pi,0,2)) ?: '?';
    @endphp
<div data-real class="hidden space-y-section-gap">
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex flex-col sm:flex-row sm:items-center gap-6">
            <div class="w-20 h-20 rounded-full bg-gold-accent text-white flex items-center justify-center font-bold text-xl shrink-0 mx-auto sm:mx-0 border border-gold-accent/30">
                {{ $pinit }}
            </div>
            <div class="flex-1 text-center sm:text-left">
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 justify-center sm:justify-start">
                    <h2 class="raliva-figure text-[26px] text-on-surface">{{ $puser->nama_lengkap ?? '-' }}</h2>
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 raliva-label text-[10px] w-fit mx-auto sm:mx-0">{{ $puser->role->nama_role ?? 'Staf Produksi' }}</span>
                </div>
                <p class="text-on-surface-variant font-body-md text-sm mt-1">{{ $puser->email ?? '-' }} • {{ $puser->nomor_telepon ?? '-' }}</p>
            </div>
            <button type="button" data-modal-open="modal-edit-profil" class="px-5 py-2.5 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium shrink-0">Edit Profil</button>
        </div>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-section-gap">
        <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Informasi Akun</h2>
            <dl class="space-y-5 font-body-md text-sm">
                <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Nama Lengkap</dt><dd class="text-on-surface font-bold text-right">{{ $puser->nama_lengkap ?? '-' }}</dd></div>
                <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Email</dt><dd class="text-on-surface text-right break-all">{{ $puser->email ?? '-' }}</dd></div>
                <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Nomor HP</dt><dd class="text-on-surface text-right">{{ $puser->nomor_telepon ?? '-' }}</dd></div>
                <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Role</dt><dd><span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">Produksi</span></dd></div>
                <div class="flex justify-between gap-4 items-start"><dt class="text-on-surface-variant shrink-0">Status Akun</dt><dd><span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20"><span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>Aktif</span></dd></div>
            </dl>
        </section>

        <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Penugasan & Akses</h2>
            <dl class="space-y-5 font-body-md text-sm">
                <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Toko</dt><dd class="text-on-surface font-bold text-right">Raliva Atelier Jakarta</dd></div>
                <div class="flex justify-between gap-4 pb-4 border-b border-muted-border items-start">
                    <dt class="text-on-surface-variant shrink-0">Workshop</dt>
                    <dd>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-surface-container-high text-on-surface text-xs font-bold border border-outline-variant whitespace-nowrap">
                            <span class="material-symbols-outlined text-[16px] text-gold-accent">precision_manufacturing</span>
                            Atelier Produksi Raliva — Kemang
                        </span>
                        <p class="text-xs text-on-surface-variant mt-2 text-right">Anda mengelola produksi workshop ini.</p>
                    </dd>
                </div>
                <div class="pb-1"><dt class="text-on-surface-variant text-sm mb-3 block">Hak Akses Produksi</dt></div>
            </dl>
            <div class="flex flex-wrap gap-2">
                @foreach ([['assignment', 'Lihat Permintaan'], ['precision_manufacturing', 'Kelola Data Produksi'], ['fact_check', 'Pemeriksaan QC'], ['task_alt', 'Produk Selesai'], ['report', 'Barang Rusak'], ['inventory', 'Bahan Produksi'], ['history', 'Riwayat']] as $perm)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-surface-container-low border border-muted-border text-on-surface-variant text-xs font-semibold">
                        <span class="material-symbols-outlined text-[14px] text-secondary">{{ $perm[0] }}</span>
                        {{ $perm[1] }}
                    </span>
                @endforeach
            </div>
            <p class="text-xs text-on-surface-variant mt-5 flex items-start gap-2">
                <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5">lock</span>
                Role dan penugasan workshop hanya dapat diubah oleh Owner atau Super Admin.
            </p>
        </section>
    </div>

    <div id="modal-edit-profil" data-modal class="fixed inset-0 z-[70] hidden">
        <div class="absolute inset-0 bg-black/50" data-modal-close></div>
        <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[85vh] overflow-y-auto">
            <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Edit Profil</h3>
                <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form data-toast-message="Profil berhasil diperbarui." class="p-6 space-y-5">
                <div>
                    <label class="block raliva-label mb-2">Nama Lengkap</label>
                    <input type="text" value="Rini Kusuma" required class="raliva-input" />
                </div>
                <div>
                    <label class="block raliva-label mb-2">Email</label>
                    <input type="email" value="rini.kusuma@raliva.id" required class="raliva-input" />
                </div>
                <div>
                    <label class="block raliva-label mb-2">Nomor HP</label>
                    <input type="text" value="+62 812-7788-9911" required class="raliva-input" />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                    <div>
                        <label class="block raliva-label mb-2">Role</label>
                        <input type="text" value="Produksi" readonly disabled class="w-full bg-surface-container-low border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface-variant opacity-80 cursor-not-allowed" />
                    </div>
                    <div>
                        <label class="block raliva-label mb-2">Workshop</label>
                        <input type="text" value="Atelier Kemang" readonly disabled class="w-full bg-surface-container-low border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface-variant opacity-80 cursor-not-allowed" />
                    </div>
                </div>
                <p class="text-xs text-on-surface-variant flex items-start gap-2">
                    <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5">info</span>
                    Perubahan role dan penugasan tidak diizinkan pada akun Anda.
                </p>
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                    <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                    <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
