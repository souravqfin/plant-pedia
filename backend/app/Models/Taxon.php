<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Taxon extends Model
{
    protected $fillable = [
        'parent_id', 'rank_id', 'scientific_name', 'author_citation', 'year',
        'nomenclatural_status', 'is_hybrid', 'is_cultivar', 'status', 'slug',
    ];

    protected function casts(): array
    {
        return ['is_hybrid' => 'boolean', 'is_cultivar' => 'boolean', 'year' => 'integer'];
    }

    public function rank(): BelongsTo
    {
        return $this->belongsTo(TaxonomicRank::class, 'rank_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function names(): HasMany
    {
        return $this->hasMany(TaxonName::class);
    }

    public function identifiers(): HasMany
    {
        return $this->hasMany(TaxonIdentifier::class);
    }

    public function plant(): HasOne
    {
        return $this->hasOne(Plant::class);
    }
}
