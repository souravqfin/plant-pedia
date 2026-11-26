<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubmissionRevision extends Model
{
    protected $fillable = ['submission_id', 'payload', 'created_by'];

    protected function casts(): array
    {
        return ['payload' => 'array'];
    }

    public function submission(): BelongsTo
    {
        return $this->belongsTo(ContentSubmission::class, 'submission_id');
    }
}
