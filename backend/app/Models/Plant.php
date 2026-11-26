<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'taxon_id', 'slug', 'status', 'verification_status', 'completeness_score',
        'is_featured', 'is_sensitive', 'published_at', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_sensitive' => 'boolean',
            'published_at' => 'datetime',
            'completeness_score' => 'integer',
        ];
    }

    public function taxon(): BelongsTo
    {
        return $this->belongsTo(Taxon::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(PlantTranslation::class);
    }

    public function names(): HasMany
    {
        return $this->hasMany(PlantName::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(PlantCategory::class, 'plant_category_map');
    }

    public function characteristics(): HasOne
    {
        return $this->hasOne(PlantCharacteristic::class);
    }

    public function careGuide(): HasOne
    {
        return $this->hasOne(PlantCareGuide::class);
    }

    public function seasonality(): HasMany
    {
        return $this->hasMany(PlantSeasonality::class);
    }

    public function distributions(): HasMany
    {
        return $this->hasMany(PlantDistribution::class);
    }

    public function conservation(): HasMany
    {
        return $this->hasMany(PlantConservationRecord::class);
    }

    public function media(): BelongsToMany
    {
        return $this->belongsToMany(Media::class, 'plant_media')
            ->withPivot(['type', 'is_primary', 'sort'])
            ->withTimestamps()
            ->orderByPivot('sort');
    }

    public function references(): HasMany
    {
        return $this->hasMany(PlantReference::class);
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(PlantAttribute::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function translation(?string $locale = null): ?PlantTranslation
    {
        $locale ??= app()->getLocale();

        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', config('app.fallback_locale'))
            ?? $this->translations->first();
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->whereNotNull('published_at');
    }
}
