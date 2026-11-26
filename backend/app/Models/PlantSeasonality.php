<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlantSeasonality extends Model
{
    protected $table = 'plant_seasonality';

    protected $fillable = ['plant_id', 'region_id', 'event', 'start_month', 'end_month'];

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }
}
