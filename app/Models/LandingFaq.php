<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class LandingFaq extends Model
{
    protected $primaryKey = 'landing_faq_id';

    protected $fillable = [
        'pertanyaan_id',
        'jawaban_id',
        'pertanyaan_en',
        'jawaban_en',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'urutan' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeTerurut(Builder $query): Builder
    {
        return $query->orderBy('urutan')->orderBy('landing_faq_id');
    }

    public function getPertanyaanAttribute(): string
    {
        $english = (string) ($this->attributes['pertanyaan_en'] ?? '');

        return app()->getLocale() === 'en' && $english !== ''
            ? $english
            : (string) ($this->attributes['pertanyaan_id'] ?? '');
    }

    public function getJawabanAttribute(): string
    {
        $english = (string) ($this->attributes['jawaban_en'] ?? '');

        return app()->getLocale() === 'en' && $english !== ''
            ? $english
            : (string) ($this->attributes['jawaban_id'] ?? '');
    }
}
