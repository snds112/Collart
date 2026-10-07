<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    protected $table = 'post';
    protected $fillable = [
        'art_type',
        'type',
        'caption',
        'like_count',
    ];



    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }

    public function likes()
    {
        return $this->belongsToMany(Account::class, 'likes');
    }

    public function accounts(): BelongsToMany
    {
        return $this->belongsToMany(Account::class, 'collab', 'post_id', 'account_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tags');
    }
}
