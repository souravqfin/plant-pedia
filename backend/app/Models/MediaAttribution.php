<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediaAttribution extends Model
{
    protected $fillable = ['media_id', 'creator', 'source_url', 'license_id', 'attribution_text'];

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    public function license(): BelongsTo
    {
        return $this->belongsTo(MediaLicense::class, 'license_id');
    }
}
