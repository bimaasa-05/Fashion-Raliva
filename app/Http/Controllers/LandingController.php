<?php

namespace App\Http\Controllers;

use App\Http\Middleware\EnsureRole;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        return view('landing.index', $this->sharedData());
    }

    public function brand(): View
    {
        return view('landing.brand', $this->sharedData());
    }

    public function openStore(): View
    {
        return view('landing.open-store', $this->sharedData());
    }

    /**
     * Data kontak dan CTA yang dipakai ketiga halaman landing.
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

        return [
            'emailSupport' => $email,
            'whatsappSupport' => $whatsappRaw,
            'whatsappDigits' => $whatsappDigits,
            'instagramSupport' => $instagram,
            'instagramUrl' => $instagramUrl,
            'commissionPercent' => (float) Setting::get(Setting::KOMISI_PERSEN_DEFAULT, '5'),
            'dashboardUrl' => $dashboardUrl,
        ];
    }
}
