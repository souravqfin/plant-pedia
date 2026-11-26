<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlantTranslation extends Model
{
    protected $fillable = [
        'plant_id', 'locale', 'common_name', 'description', 'habitat',
        'uses', 'care_summary', 'seo_title', 'seo_description',
    ];

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }
}
