<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class HelpFaq extends Model
{
    protected $primaryKey = 'help_faq_id';

    protected $fillable = [
        'help_category_id',
        'pertanyaan',
        'jawaban',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'urutan' => 'integer',
        'is_active' => 'boolean',
    ];

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(HelpCategory::class, 'help_category_id');
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeTerurut(Builder $query): Builder
    {
        return $query->orderBy('urutan')->orderBy('help_faq_id');
    }
}
