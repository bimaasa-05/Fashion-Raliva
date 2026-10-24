{{--=== FAQ Landing ===--}}
<section id="kartu-landing-faq" data-scroll-section="landing-faq" class="snap-anchor bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 p-6 space-y-gutter card-premium">
    <div class="flex items-center justify-between gap-4 flex-wrap">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-gold-accent text-[20px]">quiz</span>
                </div>
                <h2 class="font-title-md text-title-md text-on-surface uppercase tracking-wider premium-heading">{{ __('FAQ Landing') }}</h2>
            </div>
            <p class="font-body-md text-sm text-on-surface-variant mt-2">{{ __('Pertanyaan yang tampil di section FAQ halaman landing.') }}</p>
        </div>
        <button type="button" onclick="openLandingFaqModal('modal-landing-faq-tambah')" class="px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">{{ __('+ Tambah FAQ Landing') }}</button>
    </div>

    <div class="overflow-x-auto hidden md:block">
        <table class="w-full min-w-[960px] premium-table">
            <thead>
                <tr class="border-b bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                    <th class="px-3 py-3 text-center w-12 text-[10px] font-semibold tracking-widest">{{ __('No') }}</th>
                    <th class="px-3 py-3 text-left text-[10px] font-semibold tracking-widest">{{ __('Pertanyaan (ID)') }}</th>
                    <th class="px-3 py-3 text-left text-[10px] font-semibold tracking-widest">{{ __('Question (EN)') }}</th>
                    <th class="px-3 py-3 text-center w-16 text-[10px] font-semibold tracking-widest">{{ __('Urutan') }}</th>
                    <th class="px-3 py-3 text-center text-[10px] font-semibold tracking-widest">{{ __('Status') }}</th>
                    <th class="px-3 py-3 text-right text-[10px] font-semibold tracking-widest">{{ __('Aksi') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($landingFaqs as $i => $f)
                    <tr class="border-b border-muted-border hover:bg-surface-container-low">
                        <td class="p-3 text-center font-mono text-sm">{{ $i + 1 }}</td>
                        <td class="p-3 max-w-[300px]">
                            <p class="text-sm font-semibold">{{ $f->pertanyaan_id }}</p>
                            <p class="text-xs text-on-surface-variant mt-1 line-clamp-1">{{ \Illuminate\Support\Str::limit($f->jawaban_id, 80) }}</p>
                        </td>
                        <td class="p-3 max-w-[300px]">
                            <p class="text-sm font-semibold">{{ $f->pertanyaan_en }}</p>
                            <p class="text-xs text-on-surface-variant mt-1 line-clamp-1">{{ \Illuminate\Support\Str::limit($f->jawaban_en, 80) }}</p>
                        </td>
                        <td class="p-3 text-center font-mono text-sm">{{ $f->urutan }}</td>
                        <td class="p-3 text-center"><span class="inline-flex px-2 py-1 rounded-full text-[11px] font-bold border {{ $f->is_active ? \App\Support\StatusStyle::badgeClass('aktif') : \App\Support\StatusStyle::badgeClass('nonaktif') }}">{{ $f->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td class="p-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button type="button" onclick="openLandingFaqEdit({{ $f->landing_faq_id }}, @js($f->pertanyaan_id), @js($f->jawaban_id), @js($f->pertanyaan_en), @js($f->jawaban_en), {{ $f->urutan }}, {{ $f->is_active ? 'true' : 'false' }})" class="px-2.5 py-1.5 border border-gold-accent/40 rounded-lg text-[11px] font-bold uppercase text-gold-accent hover:bg-gold-accent/10">{{ __('Edit') }}</button>
                                <button type="button" onclick="openLandingFaqDelete({{ $f->landing_faq_id }}, @js($f->pertanyaan_id))" class="px-2.5 py-1.5 border border-error/30 rounded-lg text-[11px] font-bold uppercase text-error hover:bg-error/10">{{ __('Hapus') }}</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="p-8 text-center text-on-surface-variant">{{ __('Belum ada FAQ landing.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="md:hidden space-y-gutter">
        @forelse($landingFaqs as $f)
            <div class="bg-surface-container-low border border-muted-border rounded-lg p-4">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="font-title-md text-sm text-on-surface">{{ $f->pertanyaan_id }}</p>
                        <p class="font-body-md text-xs text-on-surface-variant mt-1">{{ $f->pertanyaan_en }}</p>
                    </div>
                    <span class="inline-flex px-2 py-1 rounded-full text-[10px] font-bold border shrink-0 {{ $f->is_active ? \App\Support\StatusStyle::badgeClass('aktif') : \App\Support\StatusStyle::badgeClass('nonaktif') }}">{{ $f->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                </div>
                <p class="font-body-md text-xs text-on-surface-variant leading-relaxed mt-2 line-clamp-2">{{ $f->jawaban_id }}</p>
                <div class="flex items-center justify-between gap-3 mt-3">
                    <span class="font-label-sm text-xs text-on-surface-variant">{{ __('Urutan') }} <span class="font-mono text-on-surface font-bold">{{ $f->urutan }}</span></span>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="openLandingFaqEdit({{ $f->landing_faq_id }}, @js($f->pertanyaan_id), @js($f->jawaban_id), @js($f->pertanyaan_en), @js($f->jawaban_en), {{ $f->urutan }}, {{ $f->is_active ? 'true' : 'false' }})" class="px-3 py-1.5 border border-gold-accent/40 rounded-lg text-[11px] font-bold uppercase text-gold-accent hover:bg-gold-accent/10">{{ __('Edit') }}</button>
                        <button type="button" onclick="openLandingFaqDelete({{ $f->landing_faq_id }}, @js($f->pertanyaan_id))" class="px-3 py-1.5 border border-error/30 rounded-lg text-[11px] font-bold uppercase text-error hover:bg-error/10">{{ __('Hapus') }}</button>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center font-body-md text-sm text-on-surface-variant py-6 border border-dashed border-muted-border rounded-lg">{{ __('Belum ada FAQ landing.') }}</p>
        @endforelse
    </div>
</section>

{{-- Modal Tambah FAQ Landing --}}
@component('SuperAdmin.partials.premium-modal', [
    'id' => 'modal-landing-faq-tambah',
    'dataModal' => true,
    'icon' => 'quiz',
    'title' => __('Tambah FAQ Landing'),
    'subtitle' => __('Pertanyaan ini tampil di section FAQ halaman landing.'),
    'size' => 'lg',
])
    <form method="POST" action="{{ route('superadmin.pengaturan-sistem.landing-faq.store') }}" id="form-landing-faq-tambah" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">{{ __('Pertanyaan (ID)') }}</label>
                <input type="text" name="pertanyaan_id" required maxlength="255" class="w-full bg-surface-container-low border border-muted-border rounded-xl px-4 py-3.5 font-body-md text-sm focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" placeholder="{{ __('Apakah ada biaya pendaftaran?') }}" />
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">{{ __('Question (EN)') }}</label>
                <input type="text" name="pertanyaan_en" required maxlength="255" class="w-full bg-surface-container-low border border-muted-border rounded-xl px-4 py-3.5 font-body-md text-sm focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" placeholder="{{ __('Is there a registration fee?') }}" />
            </div>
        </div>
        <div>
            <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">{{ __('Jawaban (ID)') }}</label>
            <textarea name="jawaban_id" rows="3" required minlength="3" class="w-full bg-surface-container-low border border-muted-border rounded-xl p-4 font-body-md text-sm leading-relaxed focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" placeholder="{{ __('Tuliskan jawaban...') }}"></textarea>
        </div>
        <div>
            <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">{{ __('Answer (EN)') }}</label>
            <textarea name="jawaban_en" rows="3" required minlength="3" class="w-full bg-surface-container-low border border-muted-border rounded-xl p-4 font-body-md text-sm leading-relaxed focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" placeholder="{{ __('Write the answer...') }}"></textarea>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">{{ __('Urutan') }}</label>
                <input type="number" name="urutan" min="1" class="w-full bg-surface-container-low border border-muted-border rounded-xl px-4 py-3.5 font-body-md text-sm focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" placeholder="{{ __('Otomatis') }}" />
            </div>
            <div class="flex items-end">
                <p class="font-body-md text-xs text-on-surface-variant leading-relaxed">{{ __('Kosongkan urutan untuk menaruhnya di paling bawah.') }}</p>
            </div>
        </div>
        <div class="p-4 border border-gold-accent/25 rounded-xl bg-gold-accent/5">
            <p class="font-body-md text-xs text-on-surface-variant leading-relaxed">
                <span class="material-symbols-outlined text-[16px] text-gold-accent align-middle mr-1">auto_awesome</span>
                {{ __('Gunakan placeholder') }} <code class="px-1.5 py-0.5 rounded bg-surface-container-high font-mono text-[11px]">{{ '{percent}' }}</code> {{ __('di jawaban untuk menampilkan persentase komisi platform secara otomatis.') }}
            </p>
        </div>
        @slot('footer')
            <div class="flex items-center justify-end gap-3">
                <button type="button" data-modal-close class="btn-modal btn-modal-ghost">{{ __('Batal') }}</button>
                <button type="submit" form="form-landing-faq-tambah" class="btn-modal btn-modal-primary"><span class="material-symbols-outlined text-[18px]">add</span>{{ __('Tambah') }}</button>
            </div>
        @endslot
    </form>
@endcomponent

{{-- Modal Edit FAQ Landing --}}
@component('SuperAdmin.partials.premium-modal', [
    'id' => 'modal-landing-faq-edit',
    'dataModal' => true,
    'icon' => 'edit_note',
    'title' => __('Edit FAQ Landing'),
    'subtitle' => __('Perbarui pertanyaan dan jawaban untuk halaman landing.'),
    'size' => 'lg',
])
    <form id="form-landing-faq-edit" method="POST" action="" class="space-y-4">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">{{ __('Pertanyaan (ID)') }}</label>
                <input type="text" name="pertanyaan_id" id="edit-landing-faq-pertanyaan-id" required maxlength="255" class="w-full bg-surface-container-low border border-muted-border rounded-xl px-4 py-3.5 font-body-md text-sm focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" />
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">{{ __('Question (EN)') }}</label>
                <input type="text" name="pertanyaan_en" id="edit-landing-faq-pertanyaan-en" required maxlength="255" class="w-full bg-surface-container-low border border-muted-border rounded-xl px-4 py-3.5 font-body-md text-sm focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" />
            </div>
        </div>
        <div>
            <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">{{ __('Jawaban (ID)') }}</label>
            <textarea name="jawaban_id" id="edit-landing-faq-jawaban-id" rows="3" required minlength="3" class="w-full bg-surface-container-low border border-muted-border rounded-xl p-4 font-body-md text-sm leading-relaxed focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors"></textarea>
        </div>
        <div>
            <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">{{ __('Answer (EN)') }}</label>
            <textarea name="jawaban_en" id="edit-landing-faq-jawaban-en" rows="3" required minlength="3" class="w-full bg-surface-container-low border border-muted-border rounded-xl p-4 font-body-md text-sm leading-relaxed focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors"></textarea>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">{{ __('Urutan') }}</label>
                <input type="number" name="urutan" id="edit-landing-faq-urutan" min="1" class="w-full bg-surface-container-low border border-muted-border rounded-xl px-4 py-3.5 font-body-md text-sm focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" />
            </div>
            <div class="flex items-center justify-between gap-4 p-4 border border-muted-border rounded-xl bg-surface-container-low">
                <p class="font-title-md text-sm text-on-surface">{{ __('Tampilkan FAQ') }}</p>
                <label class="raliva-toggle">
                    <input type="hidden" name="is_active" value="0" />
                    <input type="checkbox" name="is_active" id="edit-landing-faq-active" value="1" class="sr-only peer" />
                    <span class="raliva-toggle-track"></span>
                    <span class="raliva-toggle-knob"></span>
                </label>
            </div>
        </div>
        <div class="p-4 border border-gold-accent/25 rounded-xl bg-gold-accent/5">
            <p class="font-body-md text-xs text-on-surface-variant leading-relaxed">
                <span class="material-symbols-outlined text-[16px] text-gold-accent align-middle mr-1">auto_awesome</span>
                {{ __('Gunakan placeholder') }} <code class="px-1.5 py-0.5 rounded bg-surface-container-high font-mono text-[11px]">{{ '{percent}' }}</code> {{ __('di jawaban untuk menampilkan persentase komisi platform secara otomatis.') }}
            </p>
        </div>
        @slot('footer')
            <div class="flex items-center justify-end gap-3">
                <button type="button" data-modal-close class="btn-modal btn-modal-ghost">{{ __('Batal') }}</button>
                <button type="submit" form="form-landing-faq-edit" class="btn-modal btn-modal-primary"><span class="material-symbols-outlined text-[18px]">save</span>{{ __('Simpan') }}</button>
            </div>
        @endslot
    </form>
@endcomponent

{{-- Modal Hapus FAQ Landing --}}
@component('SuperAdmin.partials.premium-modal', [
    'id' => 'modal-landing-faq-hapus',
    'dataModal' => true,
    'icon' => 'delete_forever',
    'title' => __('Hapus FAQ Landing'),
    'subtitle' => __('Tindakan ini tidak bisa dibatalkan.'),
])
    <form id="form-landing-faq-hapus" method="POST" action="" class="space-y-4">
        @csrf @method('DELETE')
        <div class="p-4 border border-error/25 rounded-xl bg-error/5">
            <p class="font-body-md text-sm text-on-surface">{{ __('Hapus FAQ') }} <span id="landing-faq-hapus-teks" class="font-bold text-error">—</span>?</p>
        </div>
        <p class="font-body-md text-xs text-on-surface-variant inline-flex items-start gap-2"><span class="material-symbols-outlined text-[16px] text-gold-accent mt-[1px]">info</span>{{ __('FAQ yang sudah dihapus tidak bisa dikembalikan.') }}</p>
        @slot('footer')
            <div class="flex items-center justify-end gap-3">
                <button type="button" data-modal-close class="btn-modal btn-modal-ghost">{{ __('Batal') }}</button>
                <button type="submit" form="form-landing-faq-hapus" class="btn-modal btn-modal-danger"><span class="material-symbols-outlined text-[18px]">delete</span>{{ __('Hapus') }}</button>
            </div>
        @endslot
    </form>
@endcomponent

@push('scripts')
<script>
    function openLandingFaqModal(id) {
        var el = document.getElementById(id);
        if (!el) return;
        el.classList.remove('hidden');
        el.classList.add('flex');
    }

    function openLandingFaqEdit(id, pertanyaanId, jawabanId, pertanyaanEn, jawabanEn, urutan, isActive) {
        document.getElementById('edit-landing-faq-pertanyaan-id').value = pertanyaanId || '';
        document.getElementById('edit-landing-faq-jawaban-id').value = jawabanId || '';
        document.getElementById('edit-landing-faq-pertanyaan-en').value = pertanyaanEn || '';
        document.getElementById('edit-landing-faq-jawaban-en').value = jawabanEn || '';
        document.getElementById('edit-landing-faq-urutan').value = urutan || '';
        document.getElementById('edit-landing-faq-active').checked = !!isActive;
        document.getElementById('form-landing-faq-edit').action = '{{ url('superadmin/pengaturan-sistem/landing-faq') }}/' + id;
        openLandingFaqModal('modal-landing-faq-edit');
    }

    function openLandingFaqDelete(id, pertanyaan) {
        document.getElementById('form-landing-faq-hapus').action = '{{ url('superadmin/pengaturan-sistem/landing-faq') }}/' + id;
        document.getElementById('landing-faq-hapus-teks').textContent = pertanyaan || '';
        openLandingFaqModal('modal-landing-faq-hapus');
    }
</script>
@endpush
