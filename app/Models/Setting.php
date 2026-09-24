<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $primaryKey = 'setting_id';

    public const KOMISI_PERSEN_DEFAULT = 'komisi_persen_default';

    public const PAJAK_PERSEN = 'pajak_persen';

    public const BIAYA_LAYANAN = 'biaya_layanan';

    public const MIN_PENCAIRAN = 'min_pencairan';

    public const BIAYA_PENARIKAN_SALDO = 'biaya_penarikan_saldo';

    public const SLOT_AWAL_DEFAULT = 'slot_awal_default';

    public const SLOT_HARGA_PER_SLOT = 'slot_harga_per_slot';

    public const NAMA_PLATFORM = 'nama_platform';

    public const EMAIL_SUPPORT = 'email_support';

    public const WHATSAPP_SUPPORT = 'whatsapp_support';

    public const MODERASI_OTOMATIS = 'moderasi_otomatis';

    public const MODE_MAINTENANCE = 'mode_maintenance';

    public const SYARAT_KETENTUAN = 'syarat_ketentuan';

    public const KEBIJAKAN_PRIVASI = 'kebijakan_privasi';

    public const HELP_HERO_TITLE = 'help_hero_title';

    public const HELP_HERO_SUBTITLE = 'help_hero_subtitle';

    public const HELP_HERO_SEARCH = 'help_hero_search';

    public const HELP_WHATSAPP_HOURS = 'help_whatsapp_hours';

    protected $fillable = [
        'kunci',
        'nilai',
    ];

    public static function get(string $kunci, ?string $default = null): ?string
    {
        $nilai = static::query()->where('kunci', $kunci)->value('nilai');

        return $nilai ?? $default;
    }

    public static function set(string $kunci, ?string $nilai): void
    {
        static::updateOrCreate(['kunci' => $kunci], ['nilai' => $nilai]);
    }
}
