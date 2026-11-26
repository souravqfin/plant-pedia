<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxonName extends Model
{
    protected $fillable = ['taxon_id', 'name', 'status', 'author', 'year', 'source_id'];

    public function taxon(): BelongsTo
    {
        return $this->belongsTo(Taxon::class);
    }
}
