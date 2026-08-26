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

    public const NAMA_PLATFORM = 'nama_platform';

    public const EMAIL_SUPPORT = 'email_support';

    public const MODERASI_OTOMATIS = 'moderasi_otomatis';

    public const MODE_MAINTENANCE = 'mode_maintenance';

    public const SYARAT_KETENTUAN = 'syarat_ketentuan';

    public const KEBIJAKAN_PRIVASI = 'kebijakan_privasi';

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
