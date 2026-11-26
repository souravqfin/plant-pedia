<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollectionItem extends Model
{
    protected $fillable = ['collection_id', 'plant_id', 'sort'];

    public function collection(): BelongsTo
    {
        return $this->belongsTo(UserCollection::class, 'collection_id');
    }

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }
}
