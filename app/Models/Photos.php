<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Photos extends Model
{
    protected $fillable = [
        'photo_id',
        'title',
        'description',
        'image_path',
        'created_at',
        'user_id',
        'album_id',
    ];

    public function users(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function albums(): BelongsTo{
        return $this->belongsTo(Albums::class);
    }
}
