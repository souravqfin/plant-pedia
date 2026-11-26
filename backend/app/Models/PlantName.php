<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlantName extends Model
{
    protected $fillable = ['plant_id', 'locale', 'name', 'type', 'region_id', 'source_id'];

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(CountryRegion::class, 'region_id');
    }
}
