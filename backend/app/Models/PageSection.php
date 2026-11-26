<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PageSection extends Model
{
    protected $fillable = ['page_id', 'type', 'sort', 'settings'];

    protected function casts(): array
    {
        return ['settings' => 'array', 'sort' => 'integer'];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(PageSectionTranslation::class, 'section_id');
    }

    public function localizedSettings(?string $locale = null): array
    {
        $locale ??= app()->getLocale();
        $translation = $this->translations->firstWhere('locale', $locale);

        return array_replace_recursive($this->settings ?? [], $translation?->settings ?? []);
    }
}
