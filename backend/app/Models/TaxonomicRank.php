<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaxonomicRank extends Model
{
    protected $fillable = ['name', 'level'];

    public function taxa(): HasMany
    {
        return $this->hasMany(Taxon::class, 'rank_id');
    }
}
