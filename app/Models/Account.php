<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Account extends Authenticatable
{
    protected $table = 'account';
    protected $fillable = [
        'username',
        'email',
        'password',
        'artist_status',
        'type',
        'avatar',
        'bio'

    ];

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
    /**
     * Set the user's remember token value.
     *
     * @param string|null $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    /**
     * Get the column name for the "remember token" field.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'collab', 'account_id', 'post_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function application()
    {
        return $this->has(ArtistApplication::class);
    }

    public function collabs()
    {
        return $this->hasMany(Collab::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function messaged()
    {
        return $this->hasManyThrough(
            Account::class,
            Message::class,
            'sender_id', // Local key on Account model (sender)
            'id', // Foreign key on Account model (recipient)
        );
    }

    public function receivedMessages()
    {
        return $this->hasManyThrough(
            Account::class,
            Message::class,
            'reciever_id', // Local key on Account model (recipient)
            'sender_id', // Foreign key on Account model (sender)
        );
    }


    public function followed()
    {
        //returns the accounts the user follows
        return $this->belongsToMany(Account::class, 'follows',  'follower_id', 'followed_id');
    }

    public function followers()
    {
        //returns the accounts that follow the user
        return $this->belongsToMany(Account::class, 'follows',  'followed_id', 'follower_id');
    }
}
