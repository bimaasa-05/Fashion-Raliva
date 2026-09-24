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
        $step3Done = $isAktif || $isDitolak || $isNonaktif;
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
                    <p class="text-on-surface-variant font-body-md text-sm mt-1">{{ $statusLine }}</p>
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
        <div class="mt-10 overflow-x-auto pb-2 flex justify-start md:justify-center">
            <ol class="flex min-w-0 md:min-w-[640px] max-w-3xl w-full items-center justify-center">
                @php
                    $steps = [
                        ['Pengajuan Dikirim', $hasStore ? optional($store->created_at)->translatedFormat('d M Y') : '-', $step1Done],
                        ['Verifikasi Dokumen', $hasDocs ? optional($documents->max('updated_at') ?? $store?->created_at)->translatedFormat('d M Y') : '-', $step2Done],
                        ['Review Super Admin', $step3Done ? optional($store->updated_at)->translatedFormat('d M Y') : '-', $step3Done],
                        ['Toko Aktif', $isAktif ? optional($store->updated_at)->translatedFormat('d M Y') : '-', $step4Done],
                    ];
                @endphp
                @foreach ($steps as $step)
                    @php $isErrorStep = $isDitolak && $step[0] === 'Review Super Admin'; @endphp
                    <li class="flex-1 relative flex flex-col items-center text-center">
                        @if (! $loop->last)
                            <span class="absolute top-[22px] left-[calc(50%+22px)] right-[calc(-50%+22px)] h-[3px] {{ $isErrorStep ? 'bg-error/40' : ($step[2] ? 'bg-gold-accent/60' : 'bg-outline-variant') }} rounded-full"></span>
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
        <form method="POST" action="{{ route('owner.pengajuan-toko.store') }}" enctype="multipart/form-data" class="space-y-6" data-min-dok data-dok-valid="0">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                <div>
                    <label for="pt-nama" class="block raliva-label mb-2">Nama Toko <span class="text-error">*</span></label>
                    <input id="pt-nama" name="nama_toko" type="text" value="{{ old('nama_toko') }}" required autocomplete="organization" placeholder="Contoh: Raliva Store Bandung" class="raliva-input" />
                    @error('nama_toko') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="pt-telp" class="block raliva-label mb-2">Nomor Telepon <span class="text-error">*</span></label>
                    <input id="pt-telp" name="nomor_telepon" type="tel" inputmode="tel" value="{{ old('nomor_telepon') }}" required autocomplete="tel" placeholder="08xxxxxxxxxx" class="raliva-input" />
                    @error('nomor_telepon') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="pt-kategori" class="block raliva-label mb-2">Kategori Toko</label>
                    <select id="pt-kategori" name="kategori" class="raliva-select">
                        @foreach (($storeCategories ?? collect()) as $opt)
                            <option value="{{ $opt }}" @selected(old('kategori') === $opt)>{{ $opt }}</option>
                        @endforeach
                    </select>
                    @error('kategori') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="pt-alamat" class="block raliva-label mb-2">Alamat Lengkap <span class="text-error">*</span></label>
                    <textarea id="pt-alamat" name="alamat" rows="3" required autocomplete="street-address" placeholder="Jl. Contoh No. 123, Kota, Provinsi" class="raliva-textarea">{{ old('alamat') }}</textarea>
                    @error('alamat') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <label for="pt-deskripsi" class="block raliva-label mb-2">Deskripsi Toko (Opsional)</label>
                    <textarea id="pt-deskripsi" name="deskripsi" rows="2" placeholder="Deskripsi singkat toko Anda..." class="raliva-textarea">{{ old('deskripsi') }}</textarea>
                </div>
            </div>
            <div>
                <h3 class="font-title-md text-sm text-on-surface mb-3">Dokumen Persyaratan (minimal 3 dari 4)</h3>
                <div class="flex items-center gap-3 mb-3" data-dok-progress>
                    <div class="flex-1 h-2 rounded-full bg-surface-container-high overflow-hidden">
                        <div class="h-full bg-gold-accent rounded-full transition-all duration-300" data-dok-bar style="width:0%"></div>
                    </div>
                    <span class="text-xs font-bold text-on-surface-variant whitespace-nowrap" data-dok-text>0/3 dokumen wajib</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-gutter">
                    @foreach ([['ktp', 'description', 'KTP / Identitas Owner', true], ['npwp', 'receipt_long', 'NPWP Toko', true], ['foto_depan', 'storefront', 'Foto Depan Toko', false], ['siu', 'gavel', 'Surat Izin Usaha (NIB)', true]] as $doc)
                        @php $existing = $documents->firstWhere('jenis', $doc[0]); @endphp
                        <div class="bg-surface-container-low p-4 border border-muted-border rounded-lg flex flex-col gap-3">
                            <div class="w-11 h-11 rounded-xl bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center">
                                <span class="material-symbols-outlined text-gold-accent">{{ $doc[1] }}</span>
                            </div>
                            <p class="font-title-md text-sm text-on-surface leading-snug">{{ $doc[2] }} @if($doc[3])<span class="text-error font-bold">*</span>@else<span class="text-[10px] font-normal text-on-surface-variant">(opsional)</span>@endif</p>
                            @if ($existing)
                                <span class="mt-auto inline-flex w-fit items-center gap-1.5 px-2 py-1 rounded-full border {{ \App\Support\StatusStyle::badgeClass($existing->status) }} text-[10px] font-bold uppercase">
                                    <span class="material-symbols-outlined fill text-[12px]">check_circle</span>{{ ucfirst($existing->status) }}
                                </span>
                            @else
                                <input type="file" name="{{ $doc[0] }}" accept=".jpg,.jpeg,.png,.pdf" aria-label="Unggah {{ $doc[2] }}" @if($doc[3]) data-wajib="1" @endif data-ada="0" class="mt-auto block w-full text-xs text-on-surface-variant file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-deep-onyx file:text-on-primary file:cursor-pointer" />
                                <p class="text-[11px] text-on-surface-variant">JPG / PNG / PDF, maks 5 MB.</p>
                                @error($doc[0]) <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" data-submit-dok disabled title="Pilih file dokumen dulu" class="py-3 px-8 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
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
                @php $dokValid = $documents->where('status', '!=', 'ditolak')->count(); @endphp
                <p class="text-xs text-on-surface-variant mt-1">Status: <span class="font-bold {{ $isDitolak ? 'text-error' : 'text-gold-accent' }}">{{ ucfirst($store->status) }}</span> • {{ $dokValid }} / 3 dokumen valid (dari 4 jenis)</p>
            </div>
        </div>
        @php $trioOk = collect(['ktp','npwp','siu'])->filter(fn ($j) => ($d = $documents->firstWhere('jenis', $j)) && $d->status !== 'ditolak')->values()->implode(','); @endphp
        <form method="POST" action="{{ route('owner.pengajuan-toko.store') }}" enctype="multipart/form-data" class="space-y-6" data-min-dok data-dok-valid="{{ $dokValid ?? 0 }}" data-trio-ok="{{ $trioOk }}">
            @csrf
            @if($isDitolak)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                    <div>
                        <label for="pt2-nama" class="block raliva-label mb-2">Nama Toko</label>
                        <input id="pt2-nama" name="nama_toko" type="text" value="{{ old('nama_toko', $store->nama_toko) }}" autocomplete="organization" class="raliva-input" />
                    </div>
                    <div>
                        <label for="pt2-telp" class="block raliva-label mb-2">Nomor Telepon</label>
                        <input id="pt2-telp" name="nomor_telepon" type="tel" inputmode="tel" value="{{ old('nomor_telepon', $store->nomor_telepon) }}" autocomplete="tel" class="raliva-input" />
                    </div>
                    <div>
                        <label for="pt2-kategori" class="block raliva-label mb-2">Kategori Toko</label>
                        <select id="pt2-kategori" name="kategori" class="raliva-select">
                            @php $kategoriRepair = old('kategori', $store->kategori ?? ($storeCategories->first() ?? '')); @endphp
                            @foreach (($storeCategories ?? collect()) as $opt)
                                <option value="{{ $opt }}" @selected($kategoriRepair === $opt)>{{ $opt }}</option>
                            @endforeach
                        </select>
                        @error('kategori') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="pt2-alamat" class="block raliva-label mb-2">Alamat Lengkap</label>
                        <textarea id="pt2-alamat" name="alamat" rows="2" autocomplete="street-address" class="raliva-textarea">{{ old('alamat', $store->alamat) }}</textarea>
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
                            <span class="inline-flex w-fit items-center gap-1.5 px-2 py-1 rounded-full {{ \App\Support\StatusStyle::badgeClass($existing->status) }} text-[10px] font-bold uppercase border">
                                <span class="material-symbols-outlined fill text-[12px]">{{ $existing->status === 'terverifikasi' ? 'check_circle' : ($existing->status === 'ditolak' ? 'cancel' : 'schedule') }}</span>{{ ucfirst($existing->status) }}
                            </span>
                            @if($existing->catatan)
                                <p class="text-xs mt-1"><span class="font-bold text-error">Alasan penolakan: </span><span class="font-bold text-on-surface">{{ $existing->catatan }}</span></p>
                            @endif
                            @if($existing->status === 'terverifikasi')
                                <p class="mt-auto flex items-center gap-1.5 text-[11px] text-on-surface-variant"><span class="material-symbols-outlined text-[14px] text-gold-accent">lock</span>Terverifikasi — terkunci.</p>
                            @elseif($existing->status === 'ditolak')
                                <input type="file" name="{{ $doc[0] }}" accept=".jpg,.jpeg,.png,.pdf" aria-label="Unggah ulang {{ $doc[2] }}" @if(in_array($doc[0], ['ktp','npwp','siu'], true)) data-wajib="1" @endif data-ada="0" class="mt-auto block w-full text-xs text-on-surface-variant file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-error file:text-white file:cursor-pointer" />
                                <p class="text-[11px] text-on-surface-variant">JPG / PNG / PDF, maks 5 MB.</p>
                                @error($doc[0]) <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                            @else
                                <input type="file" name="{{ $doc[0] }}" accept=".jpg,.jpeg,.png,.pdf" aria-label="Unggah ulang {{ $doc[2] }}" disabled title="Terkunci — menunggu verifikasi" class="mt-auto block w-full text-xs text-on-surface-variant file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-surface-container-high file:text-on-surface disabled:opacity-50 disabled:cursor-not-allowed disabled:file:cursor-not-allowed disabled:file:bg-surface-container-high" />
                                <p class="text-[11px] text-on-surface-variant">Menunggu verifikasi — terkunci.</p>
                                @error($doc[0]) <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                            @endif
                        @else
                            <input type="file" name="{{ $doc[0] }}" accept=".jpg,.jpeg,.png,.pdf" aria-label="Unggah {{ $doc[2] }}" @if(in_array($doc[0], ['ktp','npwp','siu'], true)) data-wajib="1" @endif data-ada="0" class="mt-auto block w-full text-xs text-on-surface-variant file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-deep-onyx file:text-on-primary file:cursor-pointer" />
                                <p class="text-[11px] text-on-surface-variant">JPG / PNG / PDF, maks 5 MB.</p>
                                @error($doc[0]) <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                        @endif
                    </div>
                @endforeach
            </div>
            @if($isDitolak)
            <div class="flex justify-end">
                <button type="submit" data-submit-dok disabled title="Pilih file dokumen dulu" class="py-3 px-8 bg-error text-white text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2 hover:opacity-90 transition-opacity disabled:opacity-50 disabled:cursor-not-allowed">
                    <span class="material-symbols-outlined text-[16px]">upload</span>Ajukan Ulang
                </button>
            </div>
            @else
            <p class="flex items-center gap-2 text-xs text-on-surface-variant bg-surface-container-low border border-muted-border rounded-lg px-4 py-3">
                <span class="material-symbols-outlined text-[16px] text-gold-accent">lock</span>
                Dokumen terkunci — sedang menunggu verifikasi Super Admin.
            </p>
            @endif
        </form>
    </section>
    @else
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-title-md text-title-md text-on-surface premium-heading">Dokumen Persyaratan</h2>
                <p class="text-on-surface-variant text-sm mt-1">Toko Anda sudah {{ $isAktif ? 'aktif' : $store->status }}. {{ $isAktif ? 'Dokumen yang kurang/ditolak bisa dilengkapi di bawah — toko tetap aktif, dokumen baru menunggu verifikasi Super Admin.' : 'Pengajuan dokumen dikunci.' }}</p>
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
                        <span class="inline-flex w-fit items-center gap-1.5 px-2 py-1 rounded-full {{ \App\Support\StatusStyle::badgeClass($existing->status) }} text-[10px] font-bold uppercase border">
                            <span class="material-symbols-outlined fill text-[12px]">{{ $existing->status === 'terverifikasi' ? 'check_circle' : 'schedule' }}</span>{{ ucfirst($existing->status) }}
                        </span>
                    @else
                        <span class="inline-flex w-fit items-center gap-1.5 px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 text-[10px] font-bold uppercase">
                            <span class="material-symbols-outlined text-[12px]">hourglass_empty</span>Menunggu
                        </span>
                    @endif
                    @if($isAktif && (!$existing || $existing->status === 'ditolak'))
                        <form method="POST" action="{{ route('owner.pengajuan-toko.reupload') }}" enctype="multipart/form-data" class="mt-auto space-y-2" data-reupload-form>
                            @csrf
                            <input type="file" name="{{ $doc[0] }}" accept=".jpg,.jpeg,.png,.pdf" aria-label="Pilih {{ $doc[2] }}" class="block w-full text-xs text-on-surface-variant file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-deep-onyx file:text-on-primary file:cursor-pointer" />
                            <button type="submit" data-reupload-submit disabled title="Pilih file dulu" class="w-full py-2 bg-deep-onyx text-on-primary text-xs font-semibold rounded-lg btn-premium disabled:opacity-50 disabled:cursor-not-allowed">Ajukan File</button>
                        </form>
                        @if($existing?->catatan)
                            <p class="text-xs mt-1"><span class="font-bold text-error">Alasan penolakan: </span><span class="font-bold text-on-surface">{{ $existing->catatan }}</span></p>
                        @endif
                    @elseif($isAktif)
                        <p class="mt-auto flex items-center gap-1.5 text-[11px] text-on-surface-variant"><span class="material-symbols-outlined text-[14px] text-gold-accent">lock</span>{{ ucfirst($existing->status) }} — terkunci.</p>
                    @endif
                </div>
            @endforeach
        </div>
    </section>
    @endif
</div>

@push('scripts')
<script>
    // Preview dokumen ala slot foto admin: kosong spinner, terisi thumbnail + centang
    document.querySelectorAll('form[action*="pengajuan-toko"] input[type="file"]').forEach((input) => {
        if (input.dataset.dokEnhanced) return;
        input.dataset.dokEnhanced = '1';
        if (input.disabled) return;
        const idle = document.createElement('div');
        idle.className = 'dok-idle mt-2 flex items-center gap-2 text-[11px] text-on-surface-variant';
        idle.innerHTML = '<span class="material-symbols-outlined text-[16px] text-on-surface-variant/60 animate-[spin_2.5s_linear_infinite] motion-reduce:animate-none">progress_activity</span><span>Belum ada file dipilih</span>';
        input.after(idle);
        const box = document.createElement('div');
        box.className = 'dok-preview mt-2 hidden items-center gap-2.5 rounded-lg border border-gold-accent/40 bg-gold-accent/5 p-2';
        box.innerHTML = `
            <span class="dok-thumb w-11 h-11 rounded-md overflow-hidden bg-surface-container-low border border-muted-border flex items-center justify-center shrink-0"></span>
            <span class="dok-nama min-w-0 flex-1 truncate text-xs text-on-surface"></span>
            <span class="w-6 h-6 rounded-full bg-secondary text-white items-center justify-center shrink-0 flex"><span class="material-symbols-outlined text-[14px]">check_circle</span></span>
            <button type="button" class="dok-batal w-6 h-6 rounded-full bg-black/50 text-white items-center justify-center hover:bg-error transition-colors shrink-0 flex" title="Batalkan file"><span class="material-symbols-outlined text-[14px]">close</span></button>
        `;
        input.after(box);
        const thumb = box.querySelector('.dok-thumb');
        const nama = box.querySelector('.dok-nama');
        box.querySelector('.dok-batal').addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            input.value = '';
            input.dispatchEvent(new Event('change', { bubbles: true }));
        });
        input.addEventListener('change', () => {
            const f = input.files && input.files[0];
            if (!f) {
                box.classList.add('hidden');
                box.classList.remove('flex');
                idle.classList.remove('hidden');
                return;
            }
            idle.classList.add('hidden');
            nama.textContent = f.name;
            thumb.innerHTML = '';
            if (f.type.startsWith('image/')) {
                const url = URL.createObjectURL(f);
                const im = document.createElement('img');
                im.src = url;
                im.alt = '';
                im.className = 'w-full h-full object-cover';
                im.onload = () => URL.revokeObjectURL(url);
                thumb.appendChild(im);
            } else {
                thumb.innerHTML = '<span class="material-symbols-outlined text-[20px] text-error">picture_as_pdf</span>';
            }
            box.classList.remove('hidden');
            box.classList.add('flex');
        });
    });

    document.querySelectorAll('form[data-reupload-form]').forEach((form) => {
        const submitBtn = form.querySelector('[data-reupload-submit], button[type="submit"]');
        const input = form.querySelector('input[type="file"]');
        const refresh = () => {
            if (!submitBtn || !input) return;
            submitBtn.disabled = !(input.files && input.files.length > 0);
        };
        input?.addEventListener('change', refresh);
        refresh();
    });

    const TRIO = ['ktp', 'npwp', 'siu'];
    const trioTerpenuhi = (form) => {
        const ok = new Set((form.getAttribute('data-trio-ok') || '').split(',').filter(Boolean));
        form.querySelectorAll('input[type="file"][data-wajib]').forEach((i) => {
            if (i.files && i.files.length > 0) ok.add(i.name);
        });
        return ok;
    };
    const refreshDokProgress = (form) => {
        const wrap = form.querySelector('[data-dok-progress]');
        if (!wrap) return;
        const n = trioTerpenuhi(form).size;
        const bar = wrap.querySelector('[data-dok-bar]');
        const text = wrap.querySelector('[data-dok-text]');
        if (bar) bar.style.width = Math.round((n / 3) * 100) + '%';
        if (text) text.textContent = n + '/3 dokumen wajib';
    };
    document.querySelectorAll('form[data-min-dok]').forEach((form) => {
        const submitBtn = form.querySelector('[data-submit-dok]');
        const refreshSubmit = () => {
            if (!submitBtn) return;
            const ada = Array.from(form.querySelectorAll('input[type="file"]:not([disabled])')).some((i) => i.files && i.files.length > 0);
            submitBtn.disabled = !ada;
        };
        form.querySelectorAll('input[type="file"]').forEach((i) => i.addEventListener('change', () => { refreshSubmit(); refreshDokProgress(form); }));
        refreshSubmit();
        refreshDokProgress(form);
        form.addEventListener('submit', (e) => {
            const valid = parseInt(form.getAttribute('data-dok-valid') || '0', 10);
            const baru = Array.from(form.querySelectorAll('input[type="file"]')).filter((i) => i.files && i.files.length > 0).length;
            if (valid + baru < 3) {
                e.preventDefault();
                window.showRalivaToast('Minimal 3 dokumen (saat ini ' + (valid + baru) + ').', 'gpp_bad');
                const kurang = TRIO.find((j) => !trioTerpenuhi(form).has(j));
                const inputKurang = kurang && form.querySelector('input[type="file"][name="' + kurang + '"]');
                const kartu = inputKurang?.closest('div.bg-surface-container-low');
                (kartu || form).scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    });
</script>
@endpush

@endsection
