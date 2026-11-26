<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlantConservationRecord extends Model
{
    protected $fillable = ['plant_id', 'iucn_category', 'national_status', 'source_id', 'assessed_at'];

    protected function casts(): array
    {
        return ['assessed_at' => 'date'];
    }

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }
}
