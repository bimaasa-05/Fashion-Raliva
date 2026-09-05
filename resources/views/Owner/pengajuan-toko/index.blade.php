@extends('layouts.owner')

@section('title', 'Pengajuan Toko')

@section('header-title', 'Pengajuan Toko')
@section('header-badge', $store?->status === 'aktif' ? 'Disetujui' : ($store?->status === 'ditolak' ? 'Ditolak' : ($store ? 'Menunggu Verifikasi' : 'Menunggu')))
@section('header-subtitle', 'Pantau status verifikasi dan riwayat pengajuan toko Anda.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="h-32 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-24 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-72 bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    @if(session('success'))
        <div class="rounded-lg border border-secondary/20 bg-secondary-container/10 px-4 py-3 text-sm text-secondary">{{ session('success') }}</div>
    @endif
    @if(session('info'))
        <div class="rounded-lg border border-outline-variant bg-surface-container-low px-4 py-3 text-sm text-on-surface-variant">{{ session('info') }}</div>
    @endif
    @if(session('error'))
        <div class="rounded-lg border border-error/20 bg-error/5 px-4 py-3 text-sm text-error">{{ session('error') }}</div>
    @endif
    {{-- Status Verifikasi --}}
    @php
        $hasStore = $store !== null;
        $hasDocs = $documents->isNotEmpty();
        $docsCount = $documents->count();
        $storeStatus = $store?->status;
        $isAktif = $storeStatus === 'aktif';
        $isPending = $storeStatus === 'pending';
        $isDitolak = $storeStatus === 'ditolak';
        $isNonaktif = $storeStatus === 'nonaktif';
        $step1Done = $hasStore;
        $step2Done = $hasStore && $hasDocs;
        $step3Done = $isPending || $isAktif || $isDitolak || $isNonaktif;
        $step4Done = $isAktif;
        $statusTitle = $isAktif ? 'Toko Telah Disetujui' : ($isDitolak ? 'Pengajuan Ditolak' : ($hasStore ? 'Pengajuan Diproses' : 'Belum Mengajukan Toko'));
        $statusLine = $hasStore
            ? 'ID Pengajuan #' . str_pad($store->store_id, 4, '0', STR_PAD_LEFT) . ' • ' . ($isAktif ? 'Aktif sejak ' : ($isDitolak ? 'Ditolak ' : 'Diajukan ')) . optional($store->created_at)->translatedFormat('d M Y') . ($isAktif ? ' oleh Super Admin' : '')
            : 'Belum ada pengajuan — silakan isi form di bawah untuk mengajukan toko Anda.';
    @endphp
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 md:p-8 card-premium">
        <div class="flex flex-col lg:flex-row lg:items-center gap-6 justify-between">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full {{ $isAktif ? 'bg-secondary-container/20 border-secondary/30' : ($isDitolak ? 'bg-error/10 border-error/20' : 'bg-surface-container-high border-outline-variant') }} border flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined fill text-[32px] {{ $isAktif ? 'text-secondary' : ($isDitolak ? 'text-error' : 'text-on-surface-variant') }}">{{ $isAktif ? 'verified' : ($isDitolak ? 'block' : 'schedule') }}</span>
                </div>
                <div>
                    <p class="text-xs font-medium text-on-surface-variant">Status Pengajuan</p>
                    <h2 class="raliva-figure text-[26px] text-on-surface mt-1">{{ $statusTitle }}</h2>
                    <p class="text-on-surface-variant font-body-md text-sm mt-1"><span class="font-bold text-on-surface">#SUB-{{ $store?->store_id ? str_pad($store->store_id, 4, '0', STR_PAD_LEFT) : '----' }}</span> &bull; {{ $statusLine }}</p>
                    @if($isDitolak && $store->alasan_penolakan)
                        <p class="text-error text-sm mt-2 bg-error/5 border border-error/20 rounded-lg px-3 py-2">Alasan: {{ $store->alasan_penolakan }}</p>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-gutter self-start lg:self-auto">
                @if($isAktif)
                    <a href="{{ route('owner.data-toko') }}" class="px-5 py-2.5 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Lihat Data Toko</a>
                @endif
            </div>
        </div>

        {{-- Timeline Real — center --}}
        <div class="mt-10 overflow-x-auto pb-2 flex justify-center">
            <ol class="flex min-w-[640px] max-w-3xl w-full items-center justify-center">
                @php
                    $steps = [
                        ['Pengajuan Dikirim', $hasStore ? optional($store->created_at)->translatedFormat('d M Y') : '-', $step1Done],
                        ['Verifikasi Dokumen', $hasDocs ? optional($documents->max('updated_at') ?? $store?->created_at)->translatedFormat('d M Y') : '-', $step2Done],
                        ['Review Super Admin', $isAktif || $isPending ? optional($store->updated_at)->translatedFormat('d M Y') : '-', $step3Done],
                        ['Toko Aktif', $isAktif ? optional($store->updated_at)->translatedFormat('d M Y') : '-', $step4Done],
                    ];
                @endphp
                @foreach ($steps as $step)
                    @php $isErrorStep = $isDitolak && $step[0] === 'Review Super Admin'; @endphp
                    <li class="flex-1 relative flex flex-col items-center text-center">
                        @if (! $loop->last)
                            <span class="absolute top-[22px] left-1/2 ml-[22px] right-0 h-[3px] {{ $isErrorStep ? 'bg-error/40' : ($step[2] ? 'bg-gold-accent/60' : 'bg-outline-variant') }} rounded-full"></span>
                        @endif
                        <div class="relative z-10 flex flex-col items-center gap-3">
                            <span class="w-11 h-11 rounded-full {{ $isErrorStep ? 'bg-error text-white' : ($step[2] ? 'bg-deep-onyx text-on-primary' : 'bg-surface-container-high text-on-surface-variant border border-outline-variant') }} flex items-center justify-center ring-4 ring-surface-container-lowest">
                                <span class="material-symbols-outlined {{ $step[2] || $isErrorStep ? 'fill' : '' }} text-[20px]">{{ $isErrorStep ? 'block' : ($step[2] ? 'check' : 'schedule') }}</span>
                            </span>
                            <div>
                                <p class="font-title-md text-sm {{ $isErrorStep ? 'text-error' : ($step[2] ? 'text-on-surface' : 'text-on-surface-variant') }} leading-tight">{{ $step[0] }}</p>
                                <p class="font-label-sm text-[10px] uppercase tracking-wider {{ $isErrorStep ? 'text-error' : 'text-on-surface-variant' }} mt-1">{{ $step[1] }}</p>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- Form Ajuan --}}
    @if (! $store)
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex items-center justify-between gap-4 mb-6">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading">Ajukan Toko Baru</h2>
            <span class="text-xs text-on-surface-variant">{{ $documents->count() }} / 4 dokumen diunggah</span>
        </div>
        @if(session('success'))
            <div class="mb-4 rounded-lg border border-secondary/20 bg-secondary-container/10 px-4 py-3 text-sm text-secondary">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 rounded-lg border border-error/20 bg-error/5 px-4 py-3 text-sm text-error">{{ session('error') }}</div>
        @endif
        <form method="POST" action="{{ route('owner.pengajuan-toko.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                <div>
                    <label class="block raliva-label mb-2">Nama Toko <span class="text-error">*</span></label>
                    <input name="nama_toko" type="text" value="{{ old('nama_toko') }}" required placeholder="Contoh: Raliva Store Bandung" class="raliva-input" />
                    @error('nama_toko') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block raliva-label mb-2">Nomor Telepon <span class="text-error">*</span></label>
                    <input name="nomor_telepon" type="text" value="{{ old('nomor_telepon') }}" required placeholder="08xxxxxxxxxx" class="raliva-input" />
                    @error('nomor_telepon') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block raliva-label mb-2">Alamat Lengkap <span class="text-error">*</span></label>
                    <textarea name="alamat" rows="3" required placeholder="Jl. Contoh No. 123, Kota, Provinsi" class="raliva-textarea">{{ old('alamat') }}</textarea>
                    @error('alamat') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block raliva-label mb-2">Deskripsi Toko (Opsional)</label>
                    <textarea name="deskripsi" rows="2" placeholder="Deskripsi singkat toko Anda..." class="raliva-textarea">{{ old('deskripsi') }}</textarea>
                </div>
            </div>
            <div>
                <h3 class="font-title-md text-sm text-on-surface mb-3">Dokumen Persyaratan (minimal 1)</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-gutter">
                    @foreach ([['ktp', 'description', 'KTP / Identitas Owner'], ['npwp', 'receipt_long', 'NPWP Toko'], ['foto_depan', 'storefront', 'Foto Depan Toko'], ['siu', 'gavel', 'Surat Izin Usaha (NIB)']] as $doc)
                        @php $existing = $documents->firstWhere('jenis', $doc[0]); @endphp
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
            </div>
            <div class="flex justify-end">
                <button type="submit" class="py-3 px-8 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">send</span>Ajukan Toko
                </button>
            </div>
        </form>
    </section>
    @elseif ($store->status === 'pending' || $store->status === 'ditolak')
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="font-title-md text-title-md text-on-surface premium-heading">Lengkapi Dokumen</h2>
                <p class="text-xs text-on-surface-variant mt-1">Status: <span class="font-bold {{ $isDitolak ? 'text-error' : 'text-gold-accent' }}">{{ ucfirst($store->status) }}</span> • {{ $documents->count() }} / 4 dokumen diunggah</p>
            </div>
        </div>
        @if(session('success'))
            <div class="mb-4 rounded-lg border border-secondary/20 bg-secondary-container/10 px-4 py-3 text-sm text-secondary">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 rounded-lg border border-error/20 bg-error/5 px-4 py-3 text-sm text-error">{{ session('error') }}</div>
        @endif
        <form method="POST" action="{{ route('owner.pengajuan-toko.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @if($isDitolak)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                    <div>
                        <label class="block raliva-label mb-2">Nama Toko</label>
                        <input name="nama_toko" type="text" value="{{ old('nama_toko', $store->nama_toko) }}" class="raliva-input" />
                    </div>
                    <div>
                        <label class="block raliva-label mb-2">Nomor Telepon</label>
                        <input name="nomor_telepon" type="text" value="{{ old('nomor_telepon', $store->nomor_telepon) }}" class="raliva-input" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="block raliva-label mb-2">Alamat Lengkap</label>
                        <textarea name="alamat" rows="2" class="raliva-textarea">{{ old('alamat', $store->alamat) }}</textarea>
                    </div>
                </div>
            @endif
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-gutter">
                @foreach ([['ktp', 'description', 'KTP / Identitas Owner'], ['npwp', 'receipt_long', 'NPWP Toko'], ['foto_depan', 'storefront', 'Foto Depan Toko'], ['siu', 'gavel', 'Surat Izin Usaha (NIB)']] as $doc)
                    @php $existing = $documents->firstWhere('jenis', $doc[0]); @endphp
                    <div class="bg-surface-container-low p-4 border border-muted-border rounded-lg flex flex-col gap-3">
                        <div class="w-11 h-11 rounded-xl bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center">
                            <span class="material-symbols-outlined text-gold-accent">{{ $doc[1] }}</span>
                        </div>
                        <p class="font-title-md text-sm text-on-surface leading-snug">{{ $doc[2] }}</p>
                        @if ($existing)
                            <span class="inline-flex w-fit items-center gap-1.5 px-2 py-1 rounded-full {{ $existing->status === 'terverifikasi' ? 'bg-secondary-container/20 text-secondary border-secondary/20' : ($existing->status === 'ditolak' ? 'bg-error/10 text-error border-error/20' : 'bg-surface-container-high text-on-surface-variant border-outline-variant') }} text-[10px] font-bold uppercase border">
                                <span class="material-symbols-outlined fill text-[12px]">{{ $existing->status === 'terverifikasi' ? 'check_circle' : ($existing->status === 'ditolak' ? 'cancel' : 'schedule') }}</span>{{ ucfirst($existing->status) }}
                            </span>
                            @if($existing->catatan)
                                <p class="text-xs text-on-surface-variant">{{ $existing->catatan }}</p>
                            @endif
                            <input type="file" name="{{ $doc[0] }}" accept=".jpg,.jpeg,.png,.pdf" class="block w-full text-xs text-on-surface-variant file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-surface-container-high file:text-on-surface file:cursor-pointer" />
                        @else
                            <input type="file" name="{{ $doc[0] }}" accept=".jpg,.jpeg,.png,.pdf" class="block w-full text-xs text-on-surface-variant file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-deep-onyx file:text-on-primary file:cursor-pointer" />
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="flex justify-end">
                <button type="submit" class="py-3 px-8 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">upload</span>{{ $isDitolak ? 'Ajukan Ulang' : 'Unggah Dokumen' }}
                </button>
            </div>
        </form>
    </section>
    @else
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-title-md text-title-md text-on-surface premium-heading">Dokumen Persyaratan</h2>
                <p class="text-on-surface-variant text-sm mt-1">Toko Anda sudah {{ $isAktif ? 'aktif' : $store->status }}. Pengajuan dokumen dikunci.</p>
            </div>
            <button type="button" disabled class="py-3 px-8 bg-surface-container-low border border-muted-border rounded-lg text-sm font-semibold text-on-surface-variant cursor-not-allowed opacity-60 flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[16px]">lock</span>{{ $isAktif ? 'Telah Aktif' : ucfirst($store->status) }}
            </button>
        </div>
        <div data-reveal-group class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-gutter mt-6">
            @foreach ([['ktp','description','KTP / Identitas Owner'],['npwp','receipt_long','NPWP Toko'],['foto_depan','storefront','Foto Depan Toko'],['siu','gavel','Surat Izin Usaha (NIB)']] as $doc)
                @php $existing = $documents->firstWhere('jenis', $doc[0]); @endphp
                <div data-reveal class="bg-surface-container-low p-5 border border-muted-border rounded-lg flex flex-col gap-4 card-premium">
                    <div class="w-11 h-11 rounded-xl bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center">
                        <span class="material-symbols-outlined text-gold-accent">{{ $doc[1] }}</span>
                    </div>
                    <div class="flex-1">
                        <p class="font-title-md text-sm text-on-surface leading-snug">{{ $doc[2] }}</p>
                    </div>
                    @if($existing)
                        <span class="inline-flex w-fit items-center gap-1.5 px-2 py-1 rounded-full {{ $existing->status === 'terverifikasi' ? 'bg-secondary-container/20 text-secondary border-secondary/20' : 'bg-surface-container-high text-on-surface-variant border-outline-variant' }} text-[10px] font-bold uppercase border">
                            <span class="material-symbols-outlined fill text-[12px]">{{ $existing->status === 'terverifikasi' ? 'check_circle' : 'schedule' }}</span>{{ ucfirst($existing->status) }}
                        </span>
                    @else
                        <span class="inline-flex w-fit items-center gap-1.5 px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant border border-outline-variant text-[10px] font-bold uppercase">
                            <span class="material-symbols-outlined text-[12px]">hourglass_empty</span>Menunggu
                        </span>
                    @endif
                </div>
            @endforeach
        </div>
    </section>
    @endif
</div>

@endsection
