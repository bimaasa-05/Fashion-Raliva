@extends('layouts.owner')

@section('title', 'Pengajuan Toko')

@section('header-title', 'Pengajuan Toko')
@section('header-badge', $store ? 'Disetujui' : 'Menunggu')
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
                    <h2 class="raliva-figure text-[26px] text-on-surface mt-1">{{ $store ? 'Toko Telah Disetujui' : 'Pengajuan Diproses' }}</h2>
                    <p class="text-on-surface-variant font-body-md text-sm mt-1">ID Pengajuan <span class="font-bold text-on-surface">#SUB-{{ $store?->store_id ? str_pad($store->store_id, 4, '0', STR_PAD_LEFT) : '----' }}</span> &bull; Disetujui {{ optional($store?->created_at)->translatedFormat('d M Y') }} oleh Super Admin</p>
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
                            <span class="text-xs text-on-surface-variant">Read-only</span>
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

@endsection
