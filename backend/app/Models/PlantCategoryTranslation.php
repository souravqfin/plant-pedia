<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlantCategoryTranslation extends Model
{
    protected $fillable = ['category_id', 'locale', 'name', 'description', 'seo_title', 'seo_description'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(PlantCategory::class, 'category_id');
    }
}
