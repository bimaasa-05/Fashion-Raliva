{{--
    Popup premium Super Admin — gaya pop up halaman Data Bank.
    Props:
      id        : string|null  id elemen root (jika ingin penargetan JS/toast)
      icon      : string       nama ikon Material Symbols (default 'info')
      title     : string       judul header
      titleId   : string|null  id tambahan pada elemen judul (untuk JS dinamis)
      subtitle  : string|null  deskripsi di bawah judul
      subtitleId: string|null  id tambahan pada elemen deskripsi
      subtitleRaw: bool       render subtitle tanpa escaping (hati-hati, hanya konten tepercaya)
      size      : sm|md|lg|xl  lebar panel (default md)
      zIndex    : int          z-index (default 70)
      close     : string|null  nama fungsi JS tutup (mis. 'closeBankForm').
                               Jika null => gunakan data-modal-close (butuh data-modal di root).
      dataModal : bool         tambahkan atribut data-modal ke root
      panelClass: string|null  kelas tambahan pada wrapper panel
--}}
@props([
    'id' => null,
    'icon' => 'info',
    'title' => '',
    'titleId' => null,
    'subtitle' => null,
    'subtitleId' => null,
    'subtitleRaw' => false,
    'size' => 'md',
    'zIndex' => 70,
    'close' => null,
    'dataModal' => false,
    'panelClass' => null,
])

@php
    $sizes = ['sm' => 'max-w-sm', 'md' => 'max-w-md', 'lg' => 'max-w-lg', 'xl' => 'max-w-2xl'];
    $closeFn = $close ? '" onclick="' . $close . '()" ' : '';
@endphp

<div {{ $attributes->merge(['id' => $id]) }}
     @if($dataModal) data-modal @endif
     class="fixed inset-0 z-[{{ $zIndex }}] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"{!! $close ? ' onclick="' . $close . '()"' : ' data-modal-close' !!}></div>
    <div class="relative w-full {{ $sizes[$size] ?? 'max-w-md' }} m-auto flex flex-col max-h-[88dvh] overflow-hidden rounded-2xl border border-muted-border shadow-2xl card-premium bg-surface-container-lowest {{ $panelClass }}">
        <div class="relative overflow-hidden banner-gradient px-6 py-5 shrink-0">
            <span class="banner-glow banner-glow-1"></span>
            <span class="banner-glow banner-glow-2"></span>
            <div class="relative flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[22px] text-white">{{ $icon }}</span>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 @if($titleId) id="{{ $titleId }}" @endif class="font-title-md text-title-md text-white">{{ $title }}</h3>
                    @if($subtitle)
                        <p @if($subtitleId) id="{{ $subtitleId }}" @endif class="font-body-md text-xs text-white/70 mt-0.5">{{ $subtitleRaw ? new \Illuminate\Support\HtmlString($subtitle) : $subtitle }}</p>
                    @endif
                </div>
                <button type="button"{!! $close ? ' onclick="' . $close . '()"' : ' data-modal-close' !!} class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 border border-white/20 flex items-center justify-center text-white shrink-0 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>
        </div>
        <div class="p-6 space-y-4 overflow-y-auto no-scrollbar min-h-0">
            {{ $slot }}
        </div>
        @isset($footer)
            <div class="relative overflow-hidden banner-gradient px-6 py-5 shrink-0 modal-footer-band">
                <span class="banner-glow banner-glow-1"></span>
                <span class="banner-glow banner-glow-2"></span>
                <div class="relative">
                    {{ $footer }}
                </div>
            </div>
        @endisset
    </div>
</div>