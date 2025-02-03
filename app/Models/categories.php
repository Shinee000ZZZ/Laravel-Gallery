<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class categories extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'id',
        'name',
        'description',
        'user_id',
        'created_at',
    ];

    public function photo_category()
    {
        return $this->hasMany(photo_category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
