@extends('layouts.owner')

@section('title', 'Data Bank')
@section('header-title', 'Data Bank')
@section('header-subtitle', 'Kelola rekening bank untuk pencairan dana.')

@section('content')
<div class="space-y-section-gap">
    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="font-title-md text-title-md premium-heading">Rekening Bank Toko</h2>
            <p class="text-xs text-on-surface-variant mt-1">{{ $store->nama_toko ?? '-' }} • {{ $bankAccounts->count() }} rekening • Pilih 1 sebagai utama untuk pencairan.</p>
        </div>
        <button type="button" data-modal-open="modal-tambah-bank" class="px-6 py-3 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium">Tambah Rekening</button>
    </section>

    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <h3 class="font-title-md text-title-md">Daftar Rekening</h3>
            <div class="relative md:w-72">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input data-table-search class="raliva-search" placeholder="Cari bank atau nomor rekening..." type="text" />
            </div>
        </div>
        <div data-table-wrap class="overflow-x-auto">
            <table class="w-full min-w-[750px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">Bank</th>
                        <th class="p-4 text-left">Nomor Rekening</th>
                        <th class="p-4 text-left">Pemilik</th>
                        <th class="p-4 text-center">Utama</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse($bankAccounts as $ba)
                        <tr data-table-row data-search="{{ strtolower($ba->bank->nama_bank.' '.$ba->nomor_rekening.' '.$ba->nama_pemilik) }}" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent">account_balance</span></div>
                                    <span class="text-on-surface font-semibold">{{ $ba->bank->nama_bank ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="p-4 font-mono text-on-surface">{{ $ba->nomor_rekening }}</td>
                            <td class="p-4 text-on-surface">{{ $ba->nama_pemilik }}</td>
                            <td class="p-4 text-center">
                                @if($ba->is_primary)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-gold-accent text-white text-[10px] font-bold uppercase">Utama</span>
                                @else
                                    <span class="text-on-surface-variant text-xs">-</span>
                                @endif
                            </td>
                            <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold uppercase border {{ $ba->status==='aktif' ? 'bg-secondary-container/20 text-secondary border-secondary/20' : 'bg-surface-container-high text-on-surface-variant border-outline-variant' }}">{{ $ba->status }}</span></td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button type="button" data-modal-open="modal-edit-bank-{{ $ba->bank_account_id }}" class="w-8 h-8 rounded-lg bg-gold-accent/10 text-gold-accent border border-gold-accent/30 hover:bg-gold-accent hover:text-white flex items-center justify-center transition-colors" title="Edit"><span class="material-symbols-outlined text-[16px]">edit</span></button>
                                    <form method="POST" action="{{ route('owner.data-bank.destroy', $ba) }}" onsubmit="return confirm('Hapus rekening {{ $ba->bank->nama_bank }} {{ $ba->nomor_rekening }}?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-error/10 text-error border border-error/20 hover:bg-error hover:text-white flex items-center justify-center transition-colors" title="Hapus"><span class="material-symbols-outlined text-[16px]">delete</span></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="p-8 text-center text-on-surface-variant">Belum ada rekening. Tambahkan rekening untuk pencairan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div data-empty-state class="hidden flex-col items-center py-12 text-center gap-3">
            <span class="material-symbols-outlined text-[40px] text-on-surface-variant">search_off</span>
            <p class="text-on-surface font-medium">Tidak ada rekening yang cocok.</p>
        </div>
    </section>
</div>

{{-- Modal Tambah --}}
<div id="modal-tambah-bank" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <form method="POST" action="{{ route('owner.data-bank.store') }}" class="relative mx-auto w-full max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl p-6">
        @csrf
        <h3 class="font-title-md text-title-md premium-heading">Tambah Rekening</h3>
        <p class="text-xs text-on-surface-variant mt-1">Rekening harus atas nama pemilik toko.</p>
        <div class="mt-6 space-y-4">
            <div>
                <label class="raliva-label">Bank</label>
                <select name="bank_id" required class="raliva-select">
                    <option value="">Pilih Bank</option>
                    @foreach($banks as $b)
                        <option value="{{ $b->bank_id }}">{{ $b->nama_bank }} ({{ $b->kode_bank }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="raliva-label">Nomor Rekening</label>
                <input name="nomor_rekening" required class="raliva-input" placeholder="1234567890" />
            </div>
            <div>
                <label class="raliva-label">Nama Pemilik Rekening</label>
                <input name="nama_pemilik" required class="raliva-input" placeholder="Nama sesuai buku tabungan" />
            </div>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_primary" value="1" class="rounded border-muted-border text-gold-accent focus:ring-gold-accent" />
                <span class="text-sm text-on-surface">Jadikan rekening utama untuk pencairan</span>
            </label>
        </div>
        <div class="flex justify-end gap-3 mt-6">
            <button type="button" data-modal-close class="px-5 py-2.5 border border-muted-border rounded-lg text-sm font-semibold">Batal</button>
            <button type="submit" class="px-5 py-2.5 bg-deep-onyx text-on-primary rounded-lg text-sm font-semibold btn-premium">Simpan</button>
        </div>
    </form>
</div>

@foreach($bankAccounts as $ba)
<div id="modal-edit-bank-{{ $ba->bank_account_id }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <form method="POST" action="{{ route('owner.data-bank.update', $ba) }}" class="relative mx-auto w-full max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl p-6">
        @csrf @method('PUT')
        <h3 class="font-title-md text-title-md premium-heading">Edit Rekening</h3>
        <div class="mt-6 space-y-4">
            <div>
                <label class="raliva-label">Bank</label>
                <select name="bank_id" required class="raliva-select">
                    @foreach($banks as $b)
                        <option value="{{ $b->bank_id }}" {{ $b->bank_id===$ba->bank_id ? 'selected' : '' }}>{{ $b->nama_bank }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="raliva-label">Nomor Rekening</label>
                <input name="nomor_rekening" value="{{ $ba->nomor_rekening }}" required class="raliva-input" />
            </div>
            <div>
                <label class="raliva-label">Nama Pemilik</label>
                <input name="nama_pemilik" value="{{ $ba->nama_pemilik }}" required class="raliva-input" />
            </div>
            <div>
                <label class="raliva-label">Status</label>
                <select name="status" class="raliva-select">
                    <option value="aktif" {{ $ba->status==='aktif'?'selected':'' }}>Aktif</option>
                    <option value="nonaktif" {{ $ba->status==='nonaktif'?'selected':'' }}>Nonaktif</option>
                </select>
            </div>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_primary" value="1" {{ $ba->is_primary ? 'checked' : '' }} class="rounded border-muted-border text-gold-accent" />
                <span class="text-sm">Jadikan utama</span>
            </label>
        </div>
        <div class="flex justify-end gap-3 mt-6">
            <button type="button" data-modal-close class="px-5 py-2.5 border rounded-lg text-sm font-semibold">Batal</button>
            <button type="submit" class="px-5 py-2.5 bg-deep-onyx text-on-primary rounded-lg text-sm font-semibold btn-premium">Simpan</button>
        </div>
    </form>
</div>
@endforeach
@endsection
