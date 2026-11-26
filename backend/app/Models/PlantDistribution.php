<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlantDistribution extends Model
{
    protected $fillable = [
        'plant_id', 'country_id', 'region_id', 'establishment_means',
        'occurrence_status', 'is_sensitive', 'source_id',
    ];

    protected function casts(): array
    {
        return ['is_sensitive' => 'boolean'];
    }

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(CountryRegion::class, 'region_id');
    }
}
