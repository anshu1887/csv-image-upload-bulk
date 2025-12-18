<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $fillable = [
        'session_uuid',
        'filename',
        'total_size',
        'status'
    ];

    public function chunks()
    {
        return $this->hasMany(UploadChunk::class);
    }
}
