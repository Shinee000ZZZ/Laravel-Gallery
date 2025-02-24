<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Photos extends Model
{

    use HasFactory;

    use SoftDeletes;

    protected $primaryKey = 'photo_id';

    protected $withCount = ['likes', 'comments'];

    protected $fillable = [
        'photo_id',
        'title',
        'description',
        'image_path',
        'created_at',
        'updated_at',
        'trashed_at',
        'user_id',
        'album_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function albums(): BelongsTo
    {
        return $this->belongsTo(Albums::class, 'album_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comments::class, 'photo_id')->orderBy('created_at', 'asc', 'photo_id');
    }

    public function photo_category(): HasMany
    {
        return $this->hasMany(photo_category::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(categories::class, 'photo_categories', 'photo_id', 'category_id');
    }

    public function likes()
    {
        return $this->hasMany(Likes::class, 'photo_id', 'photo_id');
    }

    public function isLikedByUser($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }
}
