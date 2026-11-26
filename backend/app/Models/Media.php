<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use SoftDeletes;

    protected $table = 'media';

    protected $fillable = [
        'disk', 'path', 'mime', 'width', 'height', 'size',
        'checksum', 'alt_text', 'uploaded_by',
    ];

    protected $appends = ['url'];

    public function attribution(): HasOne
    {
        return $this->hasOne(MediaAttribution::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function plants(): BelongsToMany
    {
        return $this->belongsToMany(Plant::class, 'plant_media')
            ->withPivot(['type', 'is_primary', 'sort']);
    }

    public function getUrlAttribute(): string
    {
        if (str_starts_with($this->path, 'http')) {
            return $this->path;
        }

        return url('/storage/'.ltrim($this->path, '/'));
    }
}
