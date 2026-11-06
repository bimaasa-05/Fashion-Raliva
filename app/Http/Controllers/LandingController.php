<?php

namespace App\Http\Controllers;

use App\Http\Middleware\EnsureRole;
use App\Models\LandingFaq;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LandingController extends Controller
{
    private const LANDING_OFFER_FIELDS = [
        'eyebrow' => 'landing.open_store.eyebrow',
        'title' => 'landing.open_store.title',
        'description' => 'landing.open_store.description',
        'benefit_1' => 'landing.common.free_registration',
        'benefit_2' => 'landing.common.verified_store',
        'benefit_3' => 'landing.common.owner_dashboard',
        'requirements_title' => 'landing.open_store.requirements_title',
        'requirement_1' => 'landing.open_store.requirement_1',
        'requirement_2' => 'landing.open_store.requirement_2',
        'requirement_3' => 'landing.open_store.requirement_3',
        'requirement_note' => 'landing.open_store.requirement_note',
        'cta_primary' => 'landing.open_store.cta_primary',
        'cta_secondary' => 'landing.open_store.cta_secondary',
        'commission_note' => 'landing.open_store.commission_note',
    ];

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
            'landingOffer' => $this->landingOffer($commissionPercent),
            'faqs' => $this->landingFaqs($commissionPercent),
            'dashboardUrl' => $dashboardUrl,
        ];
    }

    /**
     * Konten section penawaran landing dari settings, dengan fallback terjemahan.
     *
     * @return array<string, mixed>
     */
    private function landingOffer(float $commissionPercent): array
    {
        $settings = Setting::query()
            ->where('kunci', 'like', 'landing_offer_%')
            ->pluck('nilai', 'kunci');

        $locale = app()->getLocale() === 'en' ? 'en' : 'id';
        $percent = (string) $commissionPercent;

        $value = function (string $field, string $translationKey) use ($settings, $locale): string {
            $primary = trim((string) $settings->get("landing_offer_{$field}_{$locale}", ''));

            if ($primary !== '') {
                return $primary;
            }

            if ($locale !== 'id') {
                $fallback = trim((string) $settings->get("landing_offer_{$field}_id", ''));

                if ($fallback !== '') {
                    return $fallback;
                }
            }

            return __($translationKey);
        };

        return [
            'eyebrow' => $value('eyebrow', self::LANDING_OFFER_FIELDS['eyebrow']),
            'title' => $value('title', self::LANDING_OFFER_FIELDS['title']),
            'description' => $value('description', self::LANDING_OFFER_FIELDS['description']),
            'benefits' => [
                ['icon' => 'rocket_launch', 'text' => $value('benefit_1', self::LANDING_OFFER_FIELDS['benefit_1'])],
                ['icon' => 'verified_user', 'text' => $value('benefit_2', self::LANDING_OFFER_FIELDS['benefit_2'])],
                ['icon' => 'dashboard', 'text' => $value('benefit_3', self::LANDING_OFFER_FIELDS['benefit_3'])],
            ],
            'requirements_title' => $value('requirements_title', self::LANDING_OFFER_FIELDS['requirements_title']),
            'requirements' => [
                $value('requirement_1', self::LANDING_OFFER_FIELDS['requirement_1']),
                $value('requirement_2', self::LANDING_OFFER_FIELDS['requirement_2']),
                $value('requirement_3', self::LANDING_OFFER_FIELDS['requirement_3']),
            ],
            'requirement_note' => $value('requirement_note', self::LANDING_OFFER_FIELDS['requirement_note']),
            'cta_primary' => $value('cta_primary', self::LANDING_OFFER_FIELDS['cta_primary']),
            'cta_secondary' => $value('cta_secondary', self::LANDING_OFFER_FIELDS['cta_secondary']),
            'commission_note' => str_replace(
                ['{percent}', ':percent'],
                $percent,
                $value('commission_note', self::LANDING_OFFER_FIELDS['commission_note'])
            ),
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
