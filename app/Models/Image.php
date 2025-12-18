<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    protected $fillable = [
        'user_id',
        'original_name',
        'path',
        'thumbnail_path',
        'medium_path',
        'size',
        'mime_type',
    ];

    /**
     * Relation: Image belongs to user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
