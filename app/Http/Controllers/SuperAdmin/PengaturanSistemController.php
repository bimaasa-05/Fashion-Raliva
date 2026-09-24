<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\HelpCategory;
use App\Models\HelpFaq;
use App\Models\Notification;
use App\Models\Setting;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengaturanSistemController extends Controller
{
    public function index()
    {
        return view('SuperAdmin.pengaturan-sistem.index', [
            'syaratKetentuan' => Setting::get(Setting::SYARAT_KETENTUAN, ''),
            'kebijakanPrivasi' => Setting::get(Setting::KEBIJAKAN_PRIVASI, ''),
            'settings' => [
                'nama_platform' => Setting::get(Setting::NAMA_PLATFORM, 'Raliva'),
                'email_support' => Setting::get(Setting::EMAIL_SUPPORT, 'support@raliva.com'),
                'whatsapp_support' => Setting::get(Setting::WHATSAPP_SUPPORT, ''),
                'komisi_persen_default' => Setting::get(Setting::KOMISI_PERSEN_DEFAULT, '5'),
                'biaya_layanan' => Setting::get(Setting::BIAYA_LAYANAN, '1000'),
                'min_pencairan' => Setting::get(Setting::MIN_PENCAIRAN, '50000'),
                'biaya_penarikan_saldo' => Setting::get(Setting::BIAYA_PENARIKAN_SALDO, '0'),
                'mode_maintenance' => Setting::get(Setting::MODE_MAINTENANCE, '0'),
                'moderasi_otomatis' => Setting::get(Setting::MODERASI_OTOMATIS, '1'),
                'maks_pengajuan_pencairan' => Setting::get('maks_pengajuan_pencairan', '3'),
                'batas_waktu_refund' => Setting::get('batas_waktu_refund', '7'),
                'konfirmasi_selesai_hari' => Setting::get('konfirmasi_selesai_hari', '5'),
            ],
            'helpCategories' => HelpCategory::terurut()->get(),
            'helpFaqs' => HelpFaq::with('category')->terurut()->get(),
            'helpHero' => [
                'title' => Setting::get(Setting::HELP_HERO_TITLE, 'How can we help?'),
                'subtitle' => Setting::get(Setting::HELP_HERO_SUBTITLE, 'Search our help center or browse popular topics below.'),
                'search' => Setting::get(Setting::HELP_HERO_SEARCH, 'Search help topics...'),
            ],
            'helpWhatsappHours' => Setting::get(Setting::HELP_WHATSAPP_HOURS, 'Mon–Fri, 09.00–17.00 WIB'),
        ]);
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'nama_platform' => 'sometimes|required|string|max:100',
            'email_support' => 'sometimes|required|email|max:100',
            'whatsapp_support' => 'sometimes|nullable|string|max:20',
            'komisi_persen_default' => 'sometimes|required|numeric|min:0|max:15',
            'biaya_layanan' => 'sometimes|required|numeric|min:0',
            'min_pencairan' => 'sometimes|required|numeric|min:0',
            'biaya_penarikan_saldo' => 'sometimes|required|numeric|min:0|max:100',
            'mode_maintenance' => 'sometimes|nullable|in:0,1',
            'moderasi_otomatis' => 'sometimes|nullable|in:0,1',
            'maks_pengajuan_pencairan' => 'sometimes|nullable|numeric|min:1',
            'batas_waktu_refund' => 'sometimes|nullable|numeric|min:1',
            'konfirmasi_selesai_hari' => 'sometimes|nullable|numeric|min:1',
        ]);

        $map = [
            'nama_platform' => Setting::NAMA_PLATFORM,
            'email_support' => Setting::EMAIL_SUPPORT,
            'whatsapp_support' => Setting::WHATSAPP_SUPPORT,
            'komisi_persen_default' => Setting::KOMISI_PERSEN_DEFAULT,
            'biaya_layanan' => Setting::BIAYA_LAYANAN,
            'min_pencairan' => Setting::MIN_PENCAIRAN,
            'biaya_penarikan_saldo' => Setting::BIAYA_PENARIKAN_SALDO,
            'mode_maintenance' => Setting::MODE_MAINTENANCE,
            'moderasi_otomatis' => Setting::MODERASI_OTOMATIS,
            'maks_pengajuan_pencairan' => 'maks_pengajuan_pencairan',
            'batas_waktu_refund' => 'batas_waktu_refund',
            'konfirmasi_selesai_hari' => 'konfirmasi_selesai_hari',
        ];

        $lama = [];
        $baru = [];
        DB::transaction(function () use ($map, $data, &$lama, &$baru) {
            foreach ($map as $field => $key) {
                if (! array_key_exists($field, $data)) {
                    continue;
                }

                $value = (string) $data[$field];

                Setting::where('kunci', $key)->lockForUpdate()->get();

                $lama[$field] = Setting::get($key);
                Setting::set($key, $value);
                $baru[$field] = $value;
            }

            if (empty($baru)) {
                return;
            }

            ActivityLogger::log(
                'setting.system.update',
                Setting::class,
                null,
                ['nilai_lama' => $lama],
                ['nilai_baru' => $baru],
                'Memperbarui pengaturan sistem platform.'
            );

            Notification::fireSelf(Notification::TIPE_SISTEM, 'Pengaturan Sistem Diperbarui', 'Pengaturan sistem platform berhasil disimpan.', route('superadmin.pengaturan-sistem'));
        });

        if (empty($baru)) {
            return back()->with('toast', ['message' => 'Tidak ada pengaturan yang dikirim.', 'icon' => 'info']);
        }

        return back()->with('toast', [
            'message' => 'Pengaturan sistem berhasil disimpan.',
            'icon' => 'task_alt',
        ]);
    }

    public function updateLegal(Request $request)
    {
        $data = $request->validate([
            'syarat_ketentuan' => 'required|string|min:10',
            'kebijakan_privasi' => 'required|string|min:10',
        ], [
            'required' => ':attribute wajib diisi.',
            'min' => ':attribute minimal 10 karakter.',
        ], [
            'syarat_ketentuan' => 'Syarat & Ketentuan',
            'kebijakan_privasi' => 'Kebijakan Privasi',
        ]);

        $lama = [
            'syarat_ketentuan' => Setting::get(Setting::SYARAT_KETENTUAN),
            'kebijakan_privasi' => Setting::get(Setting::KEBIJAKAN_PRIVASI),
        ];

        Setting::set(Setting::SYARAT_KETENTUAN, $data['syarat_ketentuan']);
        Setting::set(Setting::KEBIJAKAN_PRIVASI, $data['kebijakan_privasi']);

        ActivityLogger::log(
            'setting.legal.update',
            Setting::class,
            null,
            ['nilai_lama' => $lama],
            ['nilai_baru' => $data],
            'Memperbarui Syarat & Ketentuan dan Kebijakan Privasi platform.'
        );

        Notification::fireSelf(Notification::TIPE_SISTEM, 'Dokumen Legal Diperbarui', 'Syarat & Ketentuan dan Kebijakan Privasi diperbarui.', route('superadmin.pengaturan-sistem'));

        return back()->with('toast', [
            'message' => 'Konten Syarat & Ketentuan dan Kebijakan Privasi berhasil disimpan.',
            'icon' => 'task_alt',
        ]);
    }

    public function updateHelp(Request $request)
    {
        $data = $request->validate([
            'help_hero_title' => 'required|string|max:150',
            'help_hero_subtitle' => 'required|string|max:255',
            'help_hero_search' => 'nullable|string|max:100',
            'help_whatsapp_hours' => 'nullable|string|max:100',
        ], [], [
            'help_hero_title' => 'Judul Hero',
            'help_hero_subtitle' => 'Subjudul Hero',
            'help_hero_search' => 'Placeholder Pencarian',
            'help_whatsapp_hours' => 'Jam Operasional WhatsApp',
        ]);

        $map = [
            'help_hero_title' => Setting::HELP_HERO_TITLE,
            'help_hero_subtitle' => Setting::HELP_HERO_SUBTITLE,
            'help_hero_search' => Setting::HELP_HERO_SEARCH,
            'help_whatsapp_hours' => Setting::HELP_WHATSAPP_HOURS,
        ];

        $lama = [];
        $baru = [];
        DB::transaction(function () use ($map, $data, &$lama, &$baru) {
            foreach ($map as $field => $key) {
                $value = (string) ($data[$field] ?? '');

                Setting::where('kunci', $key)->lockForUpdate()->get();

                $lama[$field] = Setting::get($key);
                Setting::set($key, $value);
                $baru[$field] = $value;
            }

            ActivityLogger::log(
                'setting.help.hero.update',
                Setting::class,
                null,
                ['nilai_lama' => $lama],
                ['nilai_baru' => $baru],
                'Memperbarui konten hero Pusat Bantuan.'
            );

            Notification::fireSelf(Notification::TIPE_SISTEM, 'Pusat Bantuan Diperbarui', 'Konten hero Pusat Bantuan berhasil disimpan.', route('superadmin.pengaturan-sistem'));
        });

        return back()->with('toast', [
            'message' => 'Konten hero Pusat Bantuan berhasil disimpan.',
            'icon' => 'task_alt',
        ]);
    }

    public function storeHelpCategory(Request $request)
    {
        $data = $this->validateHelpCategory($request);

        $kategori = HelpCategory::create([
            'icon' => $data['icon'],
            'judul' => $data['judul'],
            'subjudul' => $data['subjudul'] ?? null,
            'urutan' => (HelpCategory::max('urutan') ?? 0) + 1,
            'is_active' => true,
        ]);

        ActivityLogger::log('help.category.create', HelpCategory::class, $kategori->help_category_id, null, $kategori->only(['icon', 'judul', 'subjudul', 'urutan']), "Menambahkan kategori bantuan \"{$kategori->judul}\".");
        Notification::fireSelf(Notification::TIPE_SISTEM, 'Kategori Bantuan Ditambahkan', "Kategori bantuan \"{$kategori->judul}\" ditambahkan.", route('superadmin.pengaturan-sistem'));

        return back()->with('toast', ['message' => "Kategori \"{$kategori->judul}\" berhasil ditambahkan.", 'icon' => 'task_alt']);
    }

    public function updateHelpCategory(Request $request, HelpCategory $helpCategory)
    {
        $data = $this->validateHelpCategory($request);

        $lama = $helpCategory->only(['icon', 'judul', 'subjudul', 'urutan', 'is_active']);

        $helpCategory->update([
            'icon' => $data['icon'],
            'judul' => $data['judul'],
            'subjudul' => $data['subjudul'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        ActivityLogger::log('help.category.update', HelpCategory::class, $helpCategory->help_category_id, $lama, $helpCategory->only(['icon', 'judul', 'subjudul', 'urutan', 'is_active']), "Mengubah kategori bantuan \"{$helpCategory->judul}\".");
        Notification::fireSelf(Notification::TIPE_SISTEM, 'Kategori Bantuan Diperbarui', "Kategori bantuan \"{$helpCategory->judul}\" diperbarui.", route('superadmin.pengaturan-sistem'));

        return back()->with('toast', ['message' => "Kategori \"{$helpCategory->judul}\" berhasil diperbarui.", 'icon' => 'task_alt']);
    }

    public function destroyHelpCategory(HelpCategory $helpCategory)
    {
        if ($helpCategory->faqs()->exists()) {
            $jumlah = $helpCategory->faqs()->count();
            return back()->with('toast', [
                'message' => "Hapus dibatalkan — kategori \"{$helpCategory->judul}\" masih dipakai {$jumlah} FAQ.",
                'icon' => 'gpp_maybe',
            ]);
        }

        $id = $helpCategory->help_category_id;
        $lama = $helpCategory->only(['icon', 'judul', 'subjudul', 'urutan']);
        $judul = $helpCategory->judul;
        $helpCategory->delete();

        ActivityLogger::log('help.category.delete', HelpCategory::class, $id, $lama, null, "Menghapus kategori bantuan \"{$judul}\".");
        Notification::fireSelf(Notification::TIPE_SISTEM, 'Kategori Bantuan Dihapus', "Kategori bantuan \"{$judul}\" dihapus.", route('superadmin.pengaturan-sistem'));

        return back()->with('toast', ['message' => "Kategori \"{$judul}\" berhasil dihapus.", 'icon' => 'delete']);
    }

    public function storeHelpFaq(Request $request)
    {
        $data = $this->validateHelpFaq($request);

        $faq = HelpFaq::create([
            'help_category_id' => $data['help_category_id'],
            'pertanyaan' => $data['pertanyaan'],
            'jawaban' => $data['jawaban'],
            'urutan' => (HelpFaq::max('urutan') ?? 0) + 1,
            'is_active' => true,
        ]);

        ActivityLogger::log('help.faq.create', HelpFaq::class, $faq->help_faq_id, null, $faq->only(['help_category_id', 'pertanyaan', 'urutan']), 'Menambahkan FAQ bantuan baru.');
        Notification::fireSelf(Notification::TIPE_SISTEM, 'FAQ Bantuan Ditambahkan', 'FAQ bantuan baru ditambahkan.', route('superadmin.pengaturan-sistem'));

        return back()->with('toast', ['message' => 'FAQ berhasil ditambahkan.', 'icon' => 'task_alt']);
    }

    public function updateHelpFaq(Request $request, HelpFaq $helpFaq)
    {
        $data = $this->validateHelpFaq($request);

        $lama = $helpFaq->only(['help_category_id', 'pertanyaan', 'jawaban', 'urutan', 'is_active']);

        $helpFaq->update([
            'help_category_id' => $data['help_category_id'],
            'pertanyaan' => $data['pertanyaan'],
            'jawaban' => $data['jawaban'],
            'is_active' => $request->boolean('is_active'),
        ]);

        ActivityLogger::log('help.faq.update', HelpFaq::class, $helpFaq->help_faq_id, $lama, $helpFaq->only(['help_category_id', 'pertanyaan', 'jawaban', 'urutan', 'is_active']), 'Mengubah FAQ bantuan.');
        Notification::fireSelf(Notification::TIPE_SISTEM, 'FAQ Bantuan Diperbarui', 'FAQ bantuan diperbarui.', route('superadmin.pengaturan-sistem'));

        return back()->with('toast', ['message' => 'FAQ berhasil diperbarui.', 'icon' => 'task_alt']);
    }

    public function destroyHelpFaq(HelpFaq $helpFaq)
    {
        $id = $helpFaq->help_faq_id;
        $pertanyaan = $helpFaq->pertanyaan;
        $lama = $helpFaq->only(['pertanyaan', 'jawaban', 'urutan']);
        $helpFaq->delete();

        ActivityLogger::log('help.faq.delete', HelpFaq::class, $id, $lama, null, 'Menghapus FAQ bantuan.');
        Notification::fireSelf(Notification::TIPE_SISTEM, 'FAQ Bantuan Dihapus', 'FAQ bantuan dihapus.', route('superadmin.pengaturan-sistem'));

        return back()->with('toast', ['message' => 'FAQ berhasil dihapus.', 'icon' => 'delete']);
    }

    private function validateHelpCategory(Request $request): array
    {
        return $request->validate([
            'icon' => 'required|string|max:50',
            'judul' => 'required|string|max:100',
            'subjudul' => 'nullable|string|max:150',
        ], [], [
            'icon' => 'Ikon',
            'judul' => 'Judul',
            'subjudul' => 'Subjudul',
        ]);
    }

    private function validateHelpFaq(Request $request): array
    {
        return $request->validate([
            'help_category_id' => 'required|integer|exists:help_categories,help_category_id',
            'pertanyaan' => 'required|string|max:255',
            'jawaban' => 'required|string|min:3',
        ], [], [
            'help_category_id' => 'Kategori',
            'pertanyaan' => 'Pertanyaan',
            'jawaban' => 'Jawaban',
        ]);
    }
}
