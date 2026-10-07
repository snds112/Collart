<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'medias';
    protected $fillable = [
        'post_id',
        'addr',
        'type',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
