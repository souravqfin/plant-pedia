<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Source extends Model
{
    protected $fillable = ['title', 'publisher', 'url', 'license', 'language', 'accessed_at', 'notes'];

    protected function casts(): array
    {
        return ['accessed_at' => 'date'];
    }

    public function plantReferences(): HasMany
    {
        return $this->hasMany(PlantReference::class);
    }
}
