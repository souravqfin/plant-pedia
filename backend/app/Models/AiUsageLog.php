<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiUsageLog extends Model
{
    protected $fillable = ['task_id', 'tokens_in', 'tokens_out', 'cost'];

    protected function casts(): array
    {
        return ['cost' => 'float'];
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(AiTask::class, 'task_id');
    }
}
