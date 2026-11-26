<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlantReference extends Model
{
    protected $fillable = ['plant_id', 'source_id', 'citation', 'url'];

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }
}
