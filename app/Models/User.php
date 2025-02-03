<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'username',
        'password',
        'email',
        'acess_level',
        'profile_photo',
        'created_at',
        'updated_at'
    ];

    protected $primaryKey = 'user_id';

    public $timestamps = false;

    public function albums(): HasMany
    {
        return $this->hasMany(Albums::class, 'user_id');
    }

    public function photos(): hasMany
    {
        return $this->hasMany(Photos::class, 'user_id');
    }

    public function totalLikes()
    {
        return $this->photos()->withCount('likes')->get()->sum('likes_count');
    }

    public function comments(): hasMany
    {
        return $this->hasMany(Comments::class);
    }

    public function likes(): hasMany
    {
        return $this->hasMany(Likes::class);
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
