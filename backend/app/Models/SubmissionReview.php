<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubmissionReview extends Model
{
    protected $fillable = ['submission_id', 'reviewer_id', 'decision', 'notes'];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(ContentSubmission::class, 'submission_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
