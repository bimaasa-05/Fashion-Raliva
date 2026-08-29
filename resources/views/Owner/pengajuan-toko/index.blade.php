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

    {{-- Upload Dokumen (hanya jika belum punya toko) --}}
    @if (! $store)
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex items-center justify-between gap-4 mb-6">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading">Ajukan Toko</h2>
            <span class="text-xs text-on-surface-variant">{{ $documents->count() }} / 4 dokumen diunggah</span>
        </div>
        <form method="POST" action="{{ route('owner.pengajuan-toko.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-gutter">
                @foreach ([['ktp', 'description', 'KTP / Identitas Owner'], ['npwp', 'receipt_long', 'NPWP Toko'], ['foto_depan', 'storefront', 'Foto Depan Toko'], ['siu', 'gavel', 'Surat Izin Usaha (NIB)']] as $doc)
                    @php
                        $existing = $documents->firstWhere('jenis', $doc[0]);
                    @endphp
                    <div class="bg-surface-container-low p-4 border border-muted-border rounded-lg flex flex-col gap-3">
                        <div class="w-11 h-11 rounded-xl bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center">
                            <span class="material-symbols-outlined text-gold-accent">{{ $doc[1] }}</span>
                        </div>
                        <p class="font-title-md text-sm text-on-surface leading-snug">{{ $doc[2] }}</p>
                        @if ($existing)
                            <span class="inline-flex w-fit items-center gap-1.5 px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">
                                <span class="material-symbols-outlined fill text-[12px]">check_circle</span>{{ ucfirst($existing->status) }}
                            </span>
                        @else
                            <input type="file" name="{{ $doc[0] }}" accept=".jpg,.jpeg,.png,.pdf" class="block w-full text-xs text-on-surface-variant file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-deep-onyx file:text-on-primary file:cursor-pointer" />
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="flex justify-end">
                <button type="submit" class="py-3 px-8 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">upload</span>Unggah Dokumen
                </button>
            </div>
        </form>
    </section>
    @else
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-title-md text-title-md text-on-surface premium-heading">Dokumen Persyaratan</h2>
                <p class="text-on-surface-variant text-sm mt-1">Toko Anda sudah aktif. Pengajuan dokumen dikunci.</p>
            </div>
            <button type="button" disabled class="py-3 px-8 bg-surface-container-low border border-muted-border rounded-lg text-sm font-semibold text-on-surface-variant cursor-not-allowed opacity-60 flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[16px]">lock</span>Ajukan Toko
            </button>
        </div>
        <div data-reveal-group class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-gutter mt-6">
            @foreach ([['description', 'KTP / Identitas Owner'], ['receipt_long', 'NPWP Toko'], ['storefront', 'Foto Depan Toko'], ['gavel', 'Surat Izin Usaha (NIB)']] as $doc)
                <div data-reveal class="bg-surface-container-low p-5 border border-muted-border rounded-lg flex flex-col gap-4 card-premium">
                    <div class="w-11 h-11 rounded-xl bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center">
                        <span class="material-symbols-outlined text-gold-accent">{{ $doc[0] }}</span>
                    </div>
                    <div class="flex-1">
                        <p class="font-title-md text-sm text-on-surface leading-snug">{{ $doc[1] }}</p>
                    </div>
                    <span class="inline-flex w-fit items-center gap-1.5 px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">
                        <span class="material-symbols-outlined fill text-[12px]">check_circle</span>Terverifikasi
                    </span>
                </div>
            @endforeach
        </div>
    </section>
    @endif
</div>

@endsection
