<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlantCharacteristic extends Model
{
    protected $fillable = [
        'plant_id', 'habit', 'height_min_cm', 'height_max_cm', 'leaf_type',
        'leaf_arrangement', 'flower_color', 'flower_shape', 'fruit_type',
        'bark_texture', 'root_type',
    ];

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }
}
