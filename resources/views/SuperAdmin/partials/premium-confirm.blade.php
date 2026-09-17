{{--
    Dialog konfirmasi premium Super Admin (hapus/tolak/setujui/selesaikan).
    Props:
      id        : string|null  id elemen root
      icon      : string       ikon tengah (default 'delete_forever')
      iconBox   : string       kelas chip ikon (default bg-error/20 border-error/30)
      iconColor : string       warna ikon (default text-error)
      zIndex    : int          z-index (default 70)
      close     : string|null  fungsi JS tutup; null => data-modal-close (butuh data-modal)
      dataModal : bool
--}}
@props([
    'id' => null,
    'icon' => 'delete_forever',
    'iconBox' => 'bg-error/20 border-error/30',
    'iconColor' => 'text-error',
    'iconWrapId' => null,
    'iconSymId' => null,
    'zIndex' => 70,
    'close' => null,
    'dataModal' => false,
])

<div {{ $attributes->merge(['id' => $id]) }}
     @if($dataModal) data-modal @endif
     class="fixed inset-0 z-[{{ $zIndex }}] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"{!! $close ? ' onclick="' . $close . '()"' : ' data-modal-close' !!}></div>
    <div class="relative w-full max-w-sm m-auto flex flex-col max-h-[85dvh] overflow-hidden rounded-2xl border border-muted-border shadow-2xl card-premium bg-surface-container-lowest">
        <div class="relative overflow-hidden banner-gradient px-6 py-5 shrink-0">
            <span class="banner-glow banner-glow-1"></span>
            <span class="banner-glow banner-glow-2"></span>
            <div class="relative flex items-center justify-center">
                <div @if($iconWrapId) id="{{ $iconWrapId }}" @endif class="w-12 h-12 rounded-full {{ $iconBox }} flex items-center justify-center">
                    <span @if($iconSymId) id="{{ $iconSymId }}" @endif class="material-symbols-outlined text-[24px] {{ $iconColor }}">{{ $icon }}</span>
                </div>
            </div>
        </div>
        <div class="overflow-y-auto no-scrollbar min-h-0">
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