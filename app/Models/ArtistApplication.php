<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtistApplication extends Model
{
    protected $table = 'artist_application_form';
    protected $fillable = [
        'fullname',
        'phone',
        'portfolio',
        'status',
        'art_description',
        'account_id',
    ];



    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
