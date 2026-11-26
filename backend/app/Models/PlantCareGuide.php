<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlantCareGuide extends Model
{
    protected $fillable = [
        'plant_id', 'light', 'water', 'soil', 'temperature_min_c',
        'temperature_max_c', 'humidity', 'notes',
    ];

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }
}
