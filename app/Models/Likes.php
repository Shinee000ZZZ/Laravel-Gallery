<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Likes extends Model
{
    protected $primaryKey = 'like_id';

    protected $table = 'likes';

    protected $fillable = [
        'like_id',
        'photo_id',
        'user_id',
        'created_at',
        'updated_at'
    ];

    public static function getLikesForPhoto($photoId)
    {
        return self::where('photo_id', $photoId)->get();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function photo(): BelongsTo
    {
        return $this->belongsTo(Photos::class, 'photo_id', 'photo_id');
    }
}
