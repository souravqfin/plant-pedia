<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContentSubmission extends Model
{
    protected $fillable = [
        'user_id', 'type', 'target_type', 'target_id', 'payload',
        'status', 'reviewer_notes', 'submitted_at', 'reviewed_at',
    ];

    protected function casts(): array
    {
        return ['payload' => 'array', 'submitted_at' => 'datetime', 'reviewed_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(SubmissionReview::class, 'submission_id');
    }
}
