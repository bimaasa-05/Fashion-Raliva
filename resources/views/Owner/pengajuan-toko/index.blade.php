@extends('layouts.owner')

@section('title', 'Pengajuan Toko')

@section('header-title', 'Pengajuan Toko')
@section('header-badge', 'Disetujui')
@section('header-subtitle', 'Pantau status verifikasi dan riwayat pengajuan toko Anda.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="h-32 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-24 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-72 bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    {{-- Status Verifikasi --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 md:p-8 card-premium">
        <div class="flex flex-col lg:flex-row lg:items-center gap-6 justify-between">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-secondary-container/20 border border-secondary/30 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined fill text-[32px] text-secondary">verified</span>
                </div>
                <div>
                    <p class="text-xs font-medium text-on-surface-variant">Status Pengajuan</p>
                    <h2 class="raliva-figure text-[26px] text-on-surface mt-1">Toko Telah Disetujui</h2>
                    <p class="text-on-surface-variant font-body-md text-sm mt-1">ID Pengajuan <span class="font-bold text-on-surface">#SUB-2024-0021</span> &bull; Disetujui 18 Maret 2026 oleh Super Admin</p>
                </div>
            </div>
            <div class="flex items-center gap-gutter self-start lg:self-auto">
                <a href="{{ route('owner.data-toko') }}" class="px-5 py-2.5 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Lihat Data Toko</a>
            </div>
        </div>

        {{-- Timeline --}}
        <div class="mt-10 overflow-x-auto pb-2">
            <ol class="flex min-w-[640px] items-start">
                @foreach ([['Pengajuan Dikirim', '12 Mar 2026', true], ['Verifikasi Dokumen', '14 Mar 2026', true], ['Review Super Admin', '18 Mar 2026', true], ['Toko Aktif', '18 Mar 2026', true]] as $i => $step)
                    <li class="flex-1 relative {{ $loop->last ? '' : 'pr-6' }}">
                        @if (! $loop->last)
                            <span class="absolute top-[22px] left-[44px] right-0 h-[3px] bg-gold-accent/60 rounded-full"></span>
                        @endif
                        <div class="relative z-10 flex flex-col items-start gap-3">
                            <span class="w-11 h-11 rounded-full bg-deep-onyx text-on-primary flex items-center justify-center ring-4 ring-surface-container-lowest">
                                <span class="material-symbols-outlined fill text-[20px]">{{ $step[2] ? 'check' : 'schedule' }}</span>
                            </span>
                            <div class="pl-0.5">
                                <p class="font-title-md text-sm text-on-surface leading-tight">{{ $step[0] }}</p>
                                <p class="font-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant mt-1">{{ $step[1] }}</p>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- Checklist Dokumen --}}
    <section>
        <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Dokumen Persyaratan</h2>
        <div data-reveal-group class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-gutter">
            @foreach ([['description', 'KTP / Identitas Owner', 'Disetujui'], ['receipt_long', 'NPWP Toko', 'Disetujui'], ['storefront', 'Foto Depan Toko', 'Disetujui'], ['gavel', 'Surat Izin Usaha (NIB)', 'Disetujui']] as $doc)
                <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-4 card-premium">
                    <div class="w-11 h-11 rounded-xl bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center">
                        <span class="material-symbols-outlined text-gold-accent">{{ $doc[0] }}</span>
                    </div>
                    <div class="flex-1">
                        <p class="font-title-md text-sm text-on-surface leading-snug">{{ $doc[1] }}</p>
                    </div>
                    <span class="inline-flex w-fit items-center gap-1.5 px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">
                        <span class="material-symbols-outlined fill text-[12px]">check_circle</span>{{ $doc[2] }}
                    </span>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Riwayat Pengajuan --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
        <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Riwayat Pengajuan</h2>
        <div data-table-wrap class="overflow-x-auto">
            <table class="premium-table w-full min-w-[760px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Versi</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Tanggal Kirim</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Catatan Super Admin</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-muted-border last:border-0 align-top">
                        <td class="py-4 px-4 font-bold text-on-surface whitespace-nowrap">v1.1</td>
                        <td class="py-4 px-4 text-on-surface-variant whitespace-nowrap">14 Mar 2026</td>
                        <td class="py-4 px-4 text-on-surface max-w-md">Semua dokumen valid. Foto toko sudah jelas dan sesuai alamat terdaftar.</td>
                        <td class="py-4 px-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Disetujui</span></td>
                        <td class="py-4 px-4 text-right"><button type="button" data-drawer-open="drawer-detail" class="text-xs font-semibold text-gold-accent hover:underline">Detail</button></td>
                    </tr>
                    <tr class="border-b border-muted-border align-top">
                        <td class="py-4 px-4 font-bold text-on-surface whitespace-nowrap">v1.0</td>
                        <td class="py-4 px-4 text-on-surface-variant whitespace-nowrap">02 Mar 2026</td>
                        <td class="py-4 px-4 text-error max-w-md">Foto depan toko tidak sesuai dengan alamat yang terdaftar pada dokumen usaha. Mohon unggah foto terbaru yang jelas menampilkan nama dan alamat toko.</td>
                        <td class="py-4 px-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Ditolak</span></td>
                        <td class="py-4 px-4 text-right">
                            <button type="button" data-modal-open="modal-kirim-ulang" class="text-xs font-semibold text-gold-accent hover:underline whitespace-nowrap">Perbaiki &amp; Kirim Ulang</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div data-empty-state class="hidden flex-col items-center py-10 text-center gap-2">
            <span class="material-symbols-outlined text-[40px] text-on-surface-variant">inbox</span>
            <p class="text-on-surface-variant font-body-md text-sm">Belum ada riwayat pengajuan.</p>
        </div>
    </section>
</div>

{{-- Modal Kirim Ulang --}}
<div id="modal-kirim-ulang" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-xl bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Perbaiki &amp; Kirim Ulang</h3>
                <p class="text-on-surface-variant font-body-md text-xs mt-1">Perbarui dokumen yang ditolak pada pengajuan v1.0.</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form data-toast-message="Pengajuan revisi berhasil dikirim untuk diverifikasi ulang." class="p-6 space-y-5">
            <div class="border border-error/20 bg-error/5 rounded-lg px-4 py-3 flex items-start gap-3">
                <span class="material-symbols-outlined text-[20px] text-error mt-0.5">error</span>
                <p class="text-error font-body-md text-sm">Alasan penolakan: Foto depan toko tidak sesuai dengan alamat yang terdaftar.</p>
            </div>
            <div>
                <label for="dokumen-revisi" class="block raliva-label mb-2">Unggah Ulang Foto Depan Toko</label>
                <label for="dokumen-revisi" class="flex flex-col items-center justify-center gap-3 border-2 border-dashed border-outline-variant rounded-lg px-6 py-10 cursor-pointer hover:border-gold-accent hover:bg-surface-container-low transition-colors group">
                    <span class="material-symbols-outlined text-[36px] text-on-surface-variant group-hover:text-gold-accent transition-colors">upload_file</span>
                    <span class="text-on-surface-variant font-body-md text-sm text-center">Tarik file ke sini atau <span class="text-gold-accent font-bold underline">pilih dari perangkat</span></span>
                    <span class="text-on-surface-variant text-xs">JPG/PNG maksimal 5 MB</span>
                </label>
                <input id="dokumen-revisi" type="file" accept=".jpg,.jpeg,.png" class="hidden" />
            </div>
            <div>
                <label for="catatan-revisi" class="block raliva-label mb-2">Catatan untuk Verifikator</label>
                <textarea id="catatan-revisi" rows="3" placeholder="Jelaskan perbaikan yang Anda lakukan..." class="raliva-textarea"></textarea>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">send</span>Kirim Ulang
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Drawer Detail Pengajuan --}}
<div id="drawer-detail" data-drawer-panel class="fixed inset-y-0 right-0 z-[80] w-full max-w-md bg-surface-container-lowest border-l border-muted-border shadow-xl translate-x-full transition-transform duration-300 ease-in-out flex flex-col">
    <div class="flex items-center justify-between px-6 py-5 border-b border-muted-border">
        <h3 class="font-title-md text-title-md text-on-surface premium-heading">Detail Pengajuan v1.1</h3>
        <button type="button" data-drawer-close class="text-on-surface-variant hover:text-on-surface transition-colors">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>
    <div class="flex-1 overflow-y-auto p-6 space-y-6">
        <dl class="space-y-4 font-body-md text-sm">
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">ID Pengajuan</dt><dd class="text-on-surface font-bold text-right">#SUB-2024-0021</dd></div>
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Diverifikasi Oleh</dt><dd class="text-on-surface text-right">Super Admin Raliva</dd></div>
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Waktu Review</dt><dd class="text-on-surface text-right">14 Mar — 18 Mar 2026</dd></div>
            <div class="flex justify-between gap-4"><dt class="text-on-surface-variant shrink-0">Status Akhir</dt><dd><span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Disetujui</span></dd></div>
        </dl>
        <div>
            <p class="raliva-label mb-3">Dokumen Terlampir</p>
            <ul class="space-y-3">
                @foreach ([['description', 'ktp_owner.pdf', '1,2 MB'], ['receipt_long', 'npwp_toko.pdf', '480 KB'], ['storefront', 'foto_depan_toko.jpg', '2,4 MB'], ['gavel', 'nib_raliva.pdf', '310 KB']] as $file)
                    <li class="flex items-center gap-3 border border-muted-border rounded-lg px-4 py-3 bg-surface-container-low">
                        <span class="material-symbols-outlined text-[20px] text-gold-accent">{{ $file[0] }}</span>
                        <div class="flex-1 min-w-0">
                            <p class="font-body-md text-sm text-on-surface truncate">{{ $file[1] }}</p>
                            <p class="text-xs text-on-surface-variant">{{ $file[2] }}</p>
                        </div>
                        <button type="button" onclick="showRalivaToast('Pratinjau dokumen dibuka (demo).', 'visibility')" class="text-on-surface-variant hover:text-gold-accent transition-colors" aria-label="Pratinjau">
                            <span class="material-symbols-outlined text-[20px]">visibility</span>
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
