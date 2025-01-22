<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class photo_category extends Model
{
    protected $table = 'photo_category';

    protected $fillable = [
        'photo_id',
        'category_id',
        'created_at',
    ];

    public function photo()
    {
        return $this->belongsTo(Photos::class);
    }

    public function category()
    {
        return $this->belongsTo(categories::class);
    }
}
