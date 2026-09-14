@php
    $susStores = \App\Support\StoreGate::suspendedStores();
    $waNumber = \App\Support\StoreGate::supportWhatsapp();
@endphp
@if(! empty($susStores))
    <div class="rounded-lg border border-error/30 bg-error/10 px-4 py-4 flex items-start gap-3" data-reveal>
        <span class="material-symbols-outlined text-error mt-0.5">lock</span>
        <div class="flex-1 min-w-0">
            <p class="font-bold text-sm text-on-surface">Toko sedang ditangguhkan</p>
            <p class="text-sm text-on-surface-variant mt-1">
                @foreach($susStores as $susStore)
                    Toko <strong class="text-on-surface">{{ $susStore->nama_toko }}</strong>
                    @if($susStore->ditangguhkan_sampai)
                        ditangguhkan sementara — akan aktif kembali {{ $susStore->ditangguhkan_sampai->translatedFormat('d F Y') }}.
                    @else
                        ditangguhkan tanpa batas waktu oleh platform.
                    @endif
                @endforeach
                Fitur dibatasi dan hanya dashboard serta profil yang dapat diakses.
            </p>
        </div>
        @if($waNumber)
            <a href="https://wa.me/{{ $waNumber }}"
               target="_blank"
               rel="noopener noreferrer"
               class="inline-flex items-center gap-1.5 shrink-0 rounded-full border border-error/30 bg-error/10 px-3 py-1.5 text-xs font-bold text-error transition-colors hover:bg-error hover:text-white">
                <span class="material-symbols-outlined text-[16px]">chat</span>Hubungi via WhatsApp
            </a>
        @else
            <span class="inline-flex items-center gap-1.5 shrink-0 rounded-full border border-error/30 bg-error/10 px-3 py-1.5 text-xs font-bold text-error">
                <span class="material-symbols-outlined text-[16px]">mail</span>Hubungi dukungan Raliva
            </span>
        @endif
    </div>
@endif