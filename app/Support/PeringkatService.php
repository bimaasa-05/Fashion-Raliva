<?php

namespace App\Support;

use App\Models\Setting;

class PeringkatService
{
    public static function defaultTiers(): array
    {
        return [
            ['min' => 100000, 'max' => 499999, 'hari' => 7],
            ['min' => 500000, 'max' => 999999, 'hari' => 14],
            ['min' => 1000000, 'max' => 1999999, 'hari' => 30],
            ['min' => 2000000, 'max' => null, 'hari' => 60],
        ];
    }

    public static function resolveHari(int $nominal): int
    {
        $raw = Setting::get(Setting::PERINGKAT_TIER, null);
        $tiers = null;

        if ($raw) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded) && $decoded !== []) {
                $tiers = $decoded;
            }
        }

        if (! is_array($tiers)) {
            $tiers = static::defaultTiers();
        }

        foreach ($tiers as $t) {
            $min = (int) ($t['min'] ?? 0);
            $max = isset($t['max']) && $t['max'] !== null ? (int) $t['max'] : null;
            $hari = (int) ($t['hari'] ?? 7);

            if ($nominal >= $min && ($max === null || $nominal <= $max)) {
                return max(1, $hari);
            }
        }

        $fallback = (int) Setting::get(Setting::PERINGKAT_HARI_DEFAULT, '7');

        return max(1, $fallback);
    }

    public static function previewText(int $nominal): string
    {
        return static::resolveHari($nominal) . ' hari';
    }
}