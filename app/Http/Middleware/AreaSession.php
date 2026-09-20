<?php

namespace App\Http\Middleware;

use App\Support\SessionArea;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Menetapkan nama cookie sesi per area SEBELUM StartSession, hanya bila
 * SESSION_MULTI_ROLE aktif. Harus di-prepend ke grup "web".
 */
class AreaSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (SessionArea::isEnabled()) {
            $area = $this->resolveArea($request);

            if ($area !== null) {
                config(['session.cookie' => SessionArea::cookieNameForArea($area)]);
            }
        }

        return $next($request);
    }

    private function resolveArea(Request $request): ?string
    {
        // (a) Prefix path area (mis. /owner/...).
        $area = SessionArea::areaForPath($request->path());
        if ($area !== null) {
            return $area;
        }

        // (b) Input tersembunyi pada form logout global.
        $input = $request->input('session_area');
        if (is_string($input) && in_array($input, SessionArea::areas(), true)) {
            return $input;
        }

        // (c) Input parameter redirect jika ada (mis. ?redirect=/owner/...).
        $redirect = $request->input('redirect');
        if (is_string($redirect)) {
            $areaRedirect = SessionArea::areaForPath($redirect);
            if ($areaRedirect !== null) {
                return $areaRedirect;
            }
        }

        // (d) Fallback Referer untuk endpoint lintas-area (/notifikasi/*, /logout, dll).
        return SessionArea::areaForReferer($request->headers->get('referer'));
    }
}
