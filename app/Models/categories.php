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
        'created_at',
    ];

    public function photo_category()
    {
        return $this->hasMany(photo_category::class);
    }
}
