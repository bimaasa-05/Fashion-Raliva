@extends('layouts.admin')

@section('title', 'Data Customer')
@section('header-title', 'Data Customer')
@section('header-badge', 'Lihat')
@section('header-subtitle', 'Data customer yang berhubungan dengan toko dan riwayat pesanannya.')

@section('content')

    <section data-reveal-group class="grid grid-cols-2 lg:grid-cols-4 gap-gutter mb-6">
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">groups</span>
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Total Customer</span>
            <span class="raliva-figure text-[26px] text-on-surface relative">{{ $customers->total() ?? 0 }}</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">person_add</span>
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Baru Bulan Ini</span>
            <span class="raliva-figure text-[26px] text-secondary relative">{{ $customers->where('created_at', '>=', now()->startOfMonth())->count() }}</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">repeat</span>
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Repeat</span>
            <span class="raliva-figure text-[26px] text-on-surface relative">{{ $customers->filter(fn($c)=>($c->total_pesanan??0)>=2)->count() }}</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">payments</span>
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Avg Belanja</span>
            <span class="raliva-figure text-[26px] text-gold-accent relative">Rp {{ number_format($customers->avg('orders_sum_grand_total') ?? 0,0,',','.') }}</span>
        </div>
    </section>

    <section data-table-scope class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <h2 class="font-title-md text-title-md text-on-surface premium-heading">Daftar Customer</h2>
        <div class="flex items-center gap-3">
            <div class="relative md:w-72">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input data-table-search class="raliva-search" placeholder="Cari nama atau email..." type="text" />
            </div>
            <button type="button" data-modal-open="modal-tambah-customer" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0">
                <span class="material-symbols-outlined text-[18px]">add</span> Tambah Customer
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[750px] premium-table">
            <thead>
                <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                    <th class="p-4 text-left">Customer</th>
                    <th class="p-4 text-left">Bergabung</th>
                    <th class="p-4 text-center">Total Pesanan</th>
                    <th class="p-4 text-right">Total Belanja</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="font-body-md text-sm">
                @forelse ($customers as $c)
                    <tr data-table-row data-search="{{ $c->nama_lengkap }} {{ $c->email }}" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4">
                            <p class="text-on-surface">{{ $c->nama_lengkap }}</p>
                            <p class="text-on-surface-variant text-xs">{{ $c->email }}</p>
                        </td>
                        <td class="p-4 text-on-surface-variant">{{ $c->created_at?->translatedFormat('M Y') }}</td>
                        <td class="p-4 text-center text-on-surface">{{ $c->total_pesanan }}</td>
                        <td class="p-4 text-right font-bold text-gold-accent">Rp {{ number_format($c->orders_sum_grand_total ?? 0, 0, ',', '.') }}</td>
                        <td class="p-4 text-right"><button type="button" data-modal-open="modal-cust-{{ $c->user_id }}" class="text-gold-accent font-label-sm text-[10px] uppercase hover:underline">Detail</button></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-8 text-center text-on-surface-variant">Belum ada customer terdaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $customers->links() }}</div>
</section>

{{-- Modal Tambah Customer --}}
<div id="modal-tambah-customer" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <form method="POST" action="{{ route('admin.customer.store') }}" class="relative mx-auto w-full max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[90vh] overflow-y-auto">
        @csrf
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <p class="raliva-label text-gold-accent">Tambah Customer</p>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">Customer Offline</h3>
                <p class="text-xs text-on-surface-variant mt-0.5">Dapat dipakai untuk pesanan offline/langsung.</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="raliva-label" for="tc-nama">Nama Lengkap <span class="text-error">*</span></label>
                <input id="tc-nama" name="nama_lengkap" required class="raliva-input" placeholder="Misal: Sari Dewi" />
            </div>
            <div>
                <label class="raliva-label" for="tc-email">Email <span class="text-error">*</span></label>
                <input id="tc-email" name="email" type="email" required class="raliva-input" placeholder="email@domain.com" />
            </div>
            <div>
                <label class="raliva-label" for="tc-telepon">No. Telepon</label>
                <input id="tc-telepon" name="nomor_telepon" type="text" class="raliva-input" placeholder="Opsional" />
            </div>
            <p class="text-xs text-on-surface-variant border border-muted-border bg-surface-container-low rounded-lg px-4 py-3">Password dibuat otomatis oleh sistem. Customer dapat masuk lewat menu Lupa Password.</p>
        </div>
        <div class="sticky bottom-0 bg-surface-container-lowest border-t border-muted-border p-4 flex justify-end gap-3">
            <button type="button" data-modal-close class="px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
            <button type="submit" class="px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Simpan Customer</button>
        </div>
    </form>
</div>

@foreach ($customers as $c)
@php
    $wa = preg_replace('/[^0-9]/', '', $c->nomor_telepon ?? '');
    if (str_starts_with($wa, '0')) $wa = '62' . substr($wa, 1);
    $waLink = $wa ? 'https://wa.me/' . $wa : null;
@endphp
<div id="modal-cust-{{ $c->user_id }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">{{ $c->nama_lengkap }}</h3>
                <p class="text-on-surface-variant text-sm mt-0.5">{{ $c->email }}</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <div class="p-6 space-y-5">
            <div class="flex items-center justify-between gap-3 bg-surface-container-low rounded-lg p-4">
                <div>
                    <p class="text-[10px] uppercase text-on-surface-variant">No. Telepon</p>
                    <p class="font-body-md text-sm text-on-surface">{{ $c->nomor_telepon ?? '-' }}</p>
                </div>
                @if ($waLink)
                    <a href="{{ $waLink }}" target="_blank" rel="noopener" title="Chat WhatsApp" aria-label="Chat WhatsApp {{ $c->nama_lengkap }}" class="w-10 h-10 shrink-0 rounded-full bg-[#25D366] text-white flex items-center justify-center shadow hover:brightness-95 transition">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    </a>
                @else
                    <span class="text-on-surface-variant text-xs">No. WA belum ada</span>
                @endif
            </div>

            <div>
                <p class="raliva-label mb-2">Riwayat Pesanan</p>
                @if ($c->orders->isEmpty())
                    <p class="text-on-surface-variant text-sm">Belum ada pesanan.</p>
                @else
                    <div class="grid grid-cols-1 gap-2">
                        @foreach ($c->orders as $o)
                            <a href="{{ route('admin.pesanan') }}?cari={{ $o->nomor_order ?? $o->order_id }}" class="flex items-center justify-between gap-3 bg-surface-container-low rounded-lg p-3 hover:border-gold-accent border border-transparent transition-colors">
                                <span class="text-on-surface font-mono text-sm">{{ $o->nomor_order ?? ('#'.$o->order_id) }}</span>
                                <span class="text-gold-accent font-bold text-sm">Rp {{ number_format((float) ($o->grand_total ?? 0), 0, ',', '.') }}</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <p class="raliva-label mb-2">Ulasan</p>
                @if ($c->reviews->isEmpty())
                    <p class="text-on-surface-variant text-sm">Belum ada ulasan.</p>
                @else
                    <div class="grid grid-cols-1 gap-2">
                        @foreach ($c->reviews as $rv)
                            <div class="bg-surface-container-low rounded-lg p-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-on-surface text-sm font-semibold">{{ $rv->rating ?? '-' }}/5</span>
                                    @if ($rv->product)
                                        <span class="text-on-surface-variant text-xs">{{ $rv->product->nama_produk ?? '' }}</span>
                                    @endif
                                </div>
                                <p class="text-on-surface-variant text-sm mt-1">{{ $rv->komentar ?? '-' }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        <div class="sticky bottom-0 bg-surface-container-lowest border-t border-muted-border p-4 flex justify-end">
            <button type="button" data-modal-close class="px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Tutup</button>
        </div>
    </div>
</div>
@endforeach
@endsection
