<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collab extends Model
{
    protected $table = 'collab';
    protected $fillable = [
        'account_id',
        'post_id'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function media()
    {
        return $this->hasManyThrough(Media::class, Post::class, 'id', 'post_id');
    }

    public function likes()
    {
        return $this->hasManyThrough(Like::class, Post::class, 'id', 'post_id');
    }

    public function comments()
    {
        return $this->hasManyThrough(Comment::class, Post::class, 'id', 'post_id');
    }
}
