<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Albums extends Model
{

    protected $primaryKey = 'album_id';

    protected $fillable = [
        'album_id',
        'title',
        'user_id',
        'description',
        'created_at',
        'user_id',
        'cover'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photos::class, 'album_id', 'album_id');
    }
}
