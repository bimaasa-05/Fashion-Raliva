<?php

namespace App\Http\Controllers;

use App\Http\Middleware\EnsureRole;
use App\Models\LandingFaq;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        return view('landing.index', $this->sharedData());
    }

    /**
     * Data kontak dan CTA yang dipakai halaman landing Owner.
     *
     * @return array<string, mixed>
     */
    private function sharedData(): array
    {
        $email = trim((string) Setting::get(Setting::EMAIL_SUPPORT, ''));
        $whatsappRaw = trim((string) Setting::get(Setting::WHATSAPP_SUPPORT, ''));
        $whatsappDigits = preg_replace('/\D+/', '', $whatsappRaw) ?: '';
        $instagram = trim((string) Setting::get(Setting::INSTAGRAM_SUPPORT, ''));

        $instagramUrl = '';
        if ($instagram !== '') {
            $instagramUrl = str_starts_with($instagram, 'http://') || str_starts_with($instagram, 'https://')
                ? $instagram
                : 'https://instagram.com/'.ltrim($instagram, '@');
        }

        $dashboardUrl = null;
        if (Auth::check()) {
            $homeRoute = EnsureRole::homeRouteFor(Auth::user()->role?->nama_role);
            $dashboardUrl = $homeRoute !== 'login' ? route($homeRoute) : route('login');
        }

        $commissionPercent = (float) Setting::get(Setting::KOMISI_PERSEN_DEFAULT, '5');

        return [
            'emailSupport' => $email,
            'whatsappSupport' => $whatsappRaw,
            'whatsappDigits' => $whatsappDigits,
            'instagramSupport' => $instagram,
            'instagramUrl' => $instagramUrl,
            'commissionPercent' => $commissionPercent,
            'faqs' => $this->landingFaqs($commissionPercent),
            'dashboardUrl' => $dashboardUrl,
        ];
    }

    /**
     * FAQ landing dari database, dengan fallback konten bawaan saat tabel kosong.
     *
     * @return array<int, array{q: string, a: string}>
     */
    private function landingFaqs(float $commissionPercent): array
    {
        $faqs = LandingFaq::aktif()->terurut()->get();

        if ($faqs->isEmpty()) {
            return [
                ['q' => __('landing.open_store.faq_1_q'), 'a' => __('landing.open_store.faq_1_a')],
                ['q' => __('landing.open_store.faq_2_q'), 'a' => __('landing.open_store.faq_2_a', ['percent' => $commissionPercent])],
                ['q' => __('landing.open_store.faq_3_q'), 'a' => __('landing.open_store.faq_3_a')],
                ['q' => __('landing.open_store.faq_4_q'), 'a' => __('landing.open_store.faq_4_a')],
                ['q' => __('landing.open_store.faq_6_q'), 'a' => __('landing.open_store.faq_6_a')],
            ];
        }

        return $faqs->map(static function (LandingFaq $faq) use ($commissionPercent): array {
            return [
                'q' => $faq->pertanyaan,
                'a' => str_replace(
                    ['{percent}', ':percent'],
                    (string) $commissionPercent,
                    $faq->jawaban
                ),
            ];
        })->all();
    }
}
