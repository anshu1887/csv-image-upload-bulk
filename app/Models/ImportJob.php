<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportJob extends Model
{
    protected $fillable = [
        'user_id',
        'file_path',
        'total_rows',
        'processed_rows',
        'failed_rows',
        'status',
        'errors',
    ];

    protected $casts = [
        'errors' => 'array',
    ];

    /**
     * Relation: Import job belongs to user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Business Logic: Update progress
     */
    public function updateProgress(int $success = 0, int $failed = 0): void
    {
        $this->increment('processed_rows', $success + $failed);
        $this->increment('failed_rows', $failed);
    }
}
