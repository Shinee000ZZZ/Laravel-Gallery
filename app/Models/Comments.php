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

    public function users(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function photos(): BelongsTo{
        return $this->belongsTo(Photos::class);
    }
}
