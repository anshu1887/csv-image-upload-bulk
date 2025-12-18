<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UploadSession extends Model
{
    protected $fillable = [
        'user_id',
        'session_uuid',
        'original_filename',
        'total_size',
        'total_chunks',
        'uploaded_chunks',
        'checksum',
        'status',
    ];

    protected static function booted()
    {
        static::creating(function ($session) {
            // Automatically generate UUID
            $session->session_uuid = Str::uuid();
        });
    }

    /**
     * Relation: UploadSession has many chunks
     */
    public function chunks(): HasMany
    {
        return $this->hasMany(UploadChunk::class);
    }

    /**
     * Relation: UploadSession belongs to a user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Business Logic: Increment uploaded chunks safely
     */
    public function incrementChunks(): void
    {
        $this->increment('uploaded_chunks');

        if ($this->uploaded_chunks >= $this->total_chunks) {
            $this->update(['status' => 'completed']);
        }
    }
}
