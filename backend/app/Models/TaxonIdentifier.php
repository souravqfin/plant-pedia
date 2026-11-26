<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxonIdentifier extends Model
{
    protected $fillable = ['taxon_id', 'provider', 'identifier'];

    public function taxon(): BelongsTo
    {
        return $this->belongsTo(Taxon::class);
    }
}
