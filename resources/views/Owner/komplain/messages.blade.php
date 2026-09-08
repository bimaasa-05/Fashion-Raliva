@extends('layouts.owner')

@section('title', 'Komplain #'.$komplain->complaint_id)
@section('header-title', 'Komplain #'.$komplain->complaint_id)
@section('header-subtitle', $komplain->subjek)

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="h-40 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-80 bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    @php
        $statusLabel = match ($komplain->status) {
            \App\Models\Complaint::STATUS_OPEN => 'Baru',
            \App\Models\Complaint::STATUS_DIPROSES => 'Dalam Penanganan',
            \App\Models\Complaint::STATUS_SELESAI => 'Selesai',
            \App\Models\Complaint::STATUS_ESKALASI => 'Eskalasi',
            \App\Models\Complaint::STATUS_DITUTUP => 'Ditutup',
            default => ucfirst($komplain->status),
        };
    @endphp

    {{-- Info Komplain --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <p class="raliva-label text-gold-accent">Komplain dari customer</p>
                <h2 class="font-title-md text-title-md text-on-surface premium-heading mt-1">{{ $komplain->subjek }}</h2>
                <div class="flex flex-wrap items-center gap-2 mt-3 text-xs text-on-surface-variant">
                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high border border-outline-variant capitalize">#{{ $komplain->complaint_id }}</span>
                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high border border-outline-variant capitalize">{{ $komplain->kategori }}</span>
                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high border border-outline-variant">
                        Pesanan #{{ $komplain->order_id ?? '-' }}
                    </span>
                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high border border-outline-variant">
                        {{ $komplain->user?->nama_lengkap ?? 'Customer' }}
                    </span>
                </div>
                <p class="font-body-sm text-sm text-on-surface mt-4 leading-relaxed max-w-2xl">{{ $komplain->deskripsi }}</p>
            </div>
            <span class="inline-flex items-center px-3 py-1.5 rounded-full {{ $komplain->status === \App\Models\Complaint::STATUS_OPEN ? 'bg-error/10 text-error border-error/20' : 'bg-secondary-container/20 text-secondary border-secondary/20' }} text-[10px] font-bold uppercase border">{{ $statusLabel }}</span>
        </div>
    </section>

    {{-- Thread Percakapan --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Percakapan</h2>
        <div class="space-y-4 mb-6 max-h-[420px] overflow-y-auto pr-2">
            @forelse ($komplain->messages as $msg)
                @php
                    $mine = $msg->sender_id === auth()->id();
                @endphp
                <div class="flex {{ $mine ? 'justify-end' : 'justify-start' }}">
                    @if ($msg->trashed())
                        <div class="max-w-[78%] md:max-w-[60%] px-4 py-2 rounded-xl border border-dashed {{ $mine ? 'bg-deep-onyx/20 border-on-surface/25' : 'bg-transparent border-outline-variant' }}">
                            <p class="font-body-sm text-sm italic {{ $mine ? 'text-on-primary/60' : 'text-on-surface-variant/70' }}">Pesan ini telah dihapus</p>
                            <p class="text-[10px] mt-1 {{ $mine ? 'text-on-primary/40' : 'text-on-surface-variant/50' }}">{{ $msg->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    @else
                        <div class="max-w-[78%] md:max-w-[60%] px-4 py-3 rounded-xl border {{ $mine ? 'bg-deep-onyx text-on-primary border-on-surface/10' : 'bg-surface-container-low border-muted-border' }}">
                            <p class="text-[10px] uppercase tracking-wider {{ $mine ? 'text-on-primary/60' : 'text-on-surface-variant' }} mb-1">
                                {{ $mine ? 'Anda' : ($msg->sender?->nama_lengkap ?? 'Customer') }} • {{ $msg->created_at->format('d M Y, H:i') }}
                                @if ($msg->edited_at)<span class="italic normal-case">(diedit)</span>@endif
                            </p>
                            <p class="font-body-sm text-sm">{{ $msg->pesan }}</p>
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-on-surface-variant text-sm text-center py-6">Belum ada percakapan.</p>
            @endforelse
        </div>

        @if (! in_array($komplain->status, [\App\Models\Complaint::STATUS_SELESAI, \App\Models\Complaint::STATUS_DITUTUP], true))
        <form method="POST" action="{{ route('owner.komplain.balas', $komplain->complaint_id) }}" class="border-t border-muted-border pt-5">
            @csrf
            <label class="block raliva-label mb-2">Balas Customer</label>
            <textarea name="pesan" rows="3" required placeholder="Tulis balasan Anda..." class="raliva-input w-full"></textarea>
            @error('pesan')
                <p class="text-error text-xs mt-1">{{ $message }}</p>
            @enderror
            <div class="flex justify-end mt-4">
                <button type="submit" class="py-2.5 px-6 bg-deep-onyx text-on-primary rounded-lg text-xs font-semibold btn-premium flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">send</span>Kirim Balasan
                </button>
            </div>
        </form>
        @else
            <p class="text-on-surface-variant text-sm border-t border-muted-border pt-5">Komplain telah ditutup dan tidak dapat dibalas lagi.</p>
        @endif
    </section>
</div>
@endsection