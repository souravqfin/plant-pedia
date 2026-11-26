<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiTask extends Model
{
    protected $fillable = [
        'provider_id', 'type', 'status', 'input', 'prompt_version',
        'error', 'started_at', 'finished_at', 'requested_by',
    ];

    protected function casts(): array
    {
        return ['input' => 'array', 'started_at' => 'datetime', 'finished_at' => 'datetime'];
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(AiProvider::class, 'provider_id');
    }

    public function drafts(): HasMany
    {
        return $this->hasMany(AiDraft::class, 'task_id');
    }

    public function suggestions(): HasMany
    {
        return $this->hasMany(AiFieldSuggestion::class, 'task_id');
    }
}
