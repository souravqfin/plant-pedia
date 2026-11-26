<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserCollection extends Model
{
    protected $fillable = ['user_id', 'name', 'description', 'is_public'];

    protected function casts(): array
    {
        return ['is_public' => 'boolean'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CollectionItem::class, 'collection_id')->orderBy('sort');
    }

    public function plants(): BelongsToMany
    {
        return $this->belongsToMany(Plant::class, 'collection_items', 'collection_id', 'plant_id')
            ->withPivot('sort');
    }
}
