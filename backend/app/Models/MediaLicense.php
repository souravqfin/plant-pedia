<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MediaLicense extends Model
{
    protected $fillable = ['code', 'name', 'url', 'requires_attribution'];

    protected function casts(): array
    {
        return ['requires_attribution' => 'boolean'];
    }

    public function attributions(): HasMany
    {
        return $this->hasMany(MediaAttribution::class, 'license_id');
    }
}
