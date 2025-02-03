<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comments extends Model
{
    protected $fillable = [
        'comment_id',
        'comment_text',
        'created_at',
        'user_id',
        'photo_id',
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class, 'user_id');
    }

    public function photos(): BelongsTo{
        return $this->belongsTo(Photos::class, 'photo_id');
    }
}
