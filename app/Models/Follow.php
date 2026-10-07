<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $fillable = []; // No fillable attributes since it's a pivot table

    public function followers()
    {
        return $this->belongsTo(Account::class, 'follower_id');
    }

    public function followed()
    {
        return $this->belongsTo(Account::class, 'followed_id');
    }
}
