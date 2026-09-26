@extends('layouts.gudang')

@section('title', 'Bahan Produk')

@section('header-title', 'Bahan Produk')
@section('header-badge', $products->total() . ' Produk')
@section('header-subtitle', 'Input bahan yang digunakan setiap produk. Bahan tampil otomatis di Produksi saat ada pesanan.')

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 md:p-6 card-premium">
        <h2 class="font-title-md text-title-md text-on-surface premium-heading mb-1">Daftar Produk</h2>
        <p class="text-xs text-on-surface-variant mb-4">Produk tanpa bahan tampil paling atas. Isi bahan agar Produksi tahu kebutuhan tiap pesanan.</p>

        {{-- Desktop --}}
        <div class="overflow-x-auto hidden md:block">
            <table class="w-full min-w-[720px] premium-table font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Produk</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Bahan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $p)
                        <tr class="border-b border-muted-border last:border-0 align-top">
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-on-surface">{{ $p->nama_produk }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $p->store?->nama_toko ?? '-' }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if ($p->bahan_count > 0)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-green-500/10 text-green-600 text-[11px] font-bold uppercase border border-green-500/30">{{ $p->bahan_count }} bahan</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-amber-500/10 text-amber-600 text-[11px] font-bold uppercase border border-amber-500/30">Belum ada</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <button type="button" onclick="openModalBahan('{{ $p->product_id }}')" class="px-3 py-1.5 bg-deep-onyx text-on-primary text-[10px] font-bold uppercase rounded hover:opacity-90 transition-opacity btn-premium">{{ $p->bahan_count > 0 ? 'Ubah Bahan' : 'Input Bahan' }}</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-12 text-center text-on-surface-variant">Belum ada produk pada toko Anda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile --}}
        <div class="md:hidden grid grid-cols-1 gap-gutter">
            @forelse ($products as $p)
                <article class="bg-surface-container-low border border-muted-border rounded-xl p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="font-bold text-on-surface">{{ $p->nama_produk }}</p>
                            <p class="text-xs text-on-surface-variant mt-0.5">{{ $p->store?->nama_toko ?? '-' }}</p>
                        </div>
                        @if ($p->bahan_count > 0)
                            <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full bg-green-500/10 text-green-600 text-[11px] font-bold uppercase border border-green-500/30">{{ $p->bahan_count }} bahan</span>
                        @else
                            <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full bg-amber-500/10 text-amber-600 text-[11px] font-bold uppercase border border-amber-500/30">Belum ada</span>
                        @endif
                    </div>
                    <button type="button" onclick="openModalBahan('{{ $p->product_id }}')" class="w-full mt-3 py-2.5 bg-deep-onyx text-on-primary text-[11px] font-bold uppercase rounded btn-premium">{{ $p->bahan_count > 0 ? 'Ubah Bahan' : 'Input Bahan' }}</button>
                </article>
            @empty
                <p class="text-on-surface-variant text-sm py-6 text-center">Belum ada produk pada toko Anda.</p>
            @endforelse
        </div>

        {{ $products->links() }}
    </section>
</div>

{{-- Modal input bahan per produk --}}
@foreach ($products as $p)
    <div id="modal-bahan-{{ $p->product_id }}" class="hidden fixed inset-0 z-[70] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" onclick="closeModalBahan('{{ $p->product_id }}')"></div>
        <form method="POST" action="{{ route('gudang.bahan-produk.store', $p) }}" class="relative mx-auto w-full max-w-md bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl max-h-[85vh] overflow-y-auto">
            @csrf
            <div class="sticky top-0 bg-surface-container-lowest border-b border-muted-border px-6 py-4 flex justify-between items-center">
                <div>
                    <h3 class="font-title-md text-title-md text-on-surface">Bahan Produksi</h3>
                    <p class="text-xs text-on-surface-variant">{{ $p->nama_produk }}</p>
                </div>
                <button type="button" onclick="closeModalBahan('{{ $p->product_id }}')" class="text-on-surface-variant"><span class="material-symbols-outlined">close</span></button>
            </div>
            <div class="p-6 space-y-4">
                <div class="space-y-3" data-bahan-rows>
                    @forelse ($p->materialRequirements as $i => $b)
                        <div class="border border-muted-border rounded-lg bg-surface-container-low p-3 space-y-2" data-bahan-row>
                            <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant">Nama bahan *
                                <input name="bahan[{{ $i }}][nama_bahan]" type="text" maxlength="150" required value="{{ $b->nama_bahan }}" class="raliva-input text-sm mt-1" />
                            </label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant">Jumlah / unit *
                                    <input name="bahan[{{ $i }}][jumlah]" type="text" inputmode="decimal" required value="{{ rtrim(rtrim(number_format((float) $b->jumlah_per_unit, 3, ',', '.'), '0'), ',') }}" class="raliva-input text-sm mt-1" />
                                </label>
                                <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant">Satuan *
                                    <select name="bahan[{{ $i }}][satuan]" class="raliva-input text-sm mt-1">
                                        @foreach ($satuanList as $satuan)
                                            <option value="{{ $satuan }}" @selected($b->satuan === $satuan)>{{ $satuan }}</option>
                                        @endforeach
                                    </select>
                                </label>
                            </div>
                            <div class="flex justify-end">
                                <button type="button" data-bahan-hapus class="text-xs font-semibold text-error hover:underline">Hapus bahan</button>
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>
                <button type="button" data-bahan-tambah="modal-bahan-{{ $p->product_id }}" class="w-full py-2.5 rounded-lg border border-dashed border-gold-accent/40 text-gold-accent text-xs font-medium hover:bg-gold-accent/5 transition-colors">+ Tambah Bahan</button>
                <p class="text-[11px] text-on-surface-variant">Menyimpan akan mengganti seluruh daftar bahan produk ini.</p>
            </div>
            <div class="sticky bottom-0 bg-surface-container-lowest border-t border-muted-border p-4 flex gap-3">
                <button type="button" onclick="closeModalBahan('{{ $p->product_id }}')" class="flex-1 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-deep-onyx text-on-primary text-xs font-semibold rounded-lg btn-premium">Simpan Bahan</button>
            </div>
        </form>
    </div>
@endforeach

@push('scripts')
<script>
    function openModalBahan(productId) {
        const modal = document.getElementById('modal-bahan-' + productId);
        if (modal) modal.classList.remove('hidden');
    }
    function closeModalBahan(productId) {
        const modal = document.getElementById('modal-bahan-' + productId);
        if (modal) modal.classList.add('hidden');
    }
    (function () {
        const satuanOptions = @json($satuanList ?? []);
        const escapeBahan = (value) => String(value ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        document.querySelectorAll('[data-bahan-rows]').forEach((box) => {
            const modal = box.closest('[id^="modal-bahan-"]');
            const modalId = modal ? modal.id : '';
            let index = box.querySelectorAll('[data-bahan-row]').length;
            const tambahBtn = document.querySelector(`[data-bahan-tambah="${modalId}"]`);
            const tambah = () => {
                const i = index++;
                const row = document.createElement('div');
                row.className = 'border border-muted-border rounded-lg bg-surface-container-low p-3 space-y-2';
                row.setAttribute('data-bahan-row', '');
                row.innerHTML = `
                    <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant">Nama bahan *
                        <input name="bahan[${i}][nama_bahan]" type="text" maxlength="150" required class="raliva-input text-sm mt-1" />
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant">Jumlah / unit *
                            <input name="bahan[${i}][jumlah]" type="text" inputmode="decimal" required placeholder="1" class="raliva-input text-sm mt-1" />
                        </label>
                        <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant">Satuan *
                            <select name="bahan[${i}][satuan]" class="raliva-input text-sm mt-1">
                                ${satuanOptions.map((s) => `<option value="${escapeBahan(s)}">${escapeBahan(s)}</option>`).join('')}
                            </select>
                        </label>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" data-bahan-hapus class="text-xs font-semibold text-error hover:underline">Hapus bahan</button>
                    </div>
                `;
                row.querySelector('[data-bahan-hapus]').addEventListener('click', () => row.remove());
                box.appendChild(row);
            };
            box.querySelectorAll('[data-bahan-hapus]').forEach((btn) => {
                btn.addEventListener('click', () => btn.closest('[data-bahan-row]').remove());
            });
            tambahBtn?.addEventListener('click', tambah);
            if (index === 0) tambah();
        });
    })();
</script>
@endpush
@endsection
