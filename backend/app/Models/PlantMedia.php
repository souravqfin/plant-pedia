<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlantMedia extends Model
{
    protected $table = 'plant_media';

    protected $fillable = ['plant_id', 'media_id', 'type', 'is_primary', 'sort'];

    protected function casts(): array
    {
        return ['is_primary' => 'boolean', 'sort' => 'integer'];
    }

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }
}
