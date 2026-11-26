<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlantAttribute extends Model
{
    protected $fillable = ['plant_id', 'key', 'value', 'source_id'];

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }
}
