<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Likes extends Model
{

    protected $primaryKey = 'like_id';

    protected $fillable = [
        'like_id',
        'created_at',
        'photo_id',
        'user_id',
    ];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function photos(): BelongsTo
    {
        return $this->belongsTo(Photos::class);
    }
}
