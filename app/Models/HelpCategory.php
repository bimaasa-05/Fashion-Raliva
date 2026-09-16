<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class HelpCategory extends Model
{
    protected $primaryKey = 'help_category_id';

    protected $fillable = [
        'icon',
        'judul',
        'subjudul',
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
        return $query->orderBy('urutan')->orderBy('help_category_id');
    }

    public function faqs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(HelpFaq::class, 'help_category_id');
    }
}
