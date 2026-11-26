<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiFieldSuggestion extends Model
{
    protected $fillable = [
        'task_id', 'plant_id', 'field', 'current_value',
        'proposed_value', 'confidence', 'status',
    ];

    protected function casts(): array
    {
        return ['confidence' => 'float'];
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(AiTask::class, 'task_id');
    }

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }
}
