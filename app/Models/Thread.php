<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function answers()
    {
        return  $this->hasMany(Answer::class);
    }

    public function user()
    {
        return  $this->belongsTo(User::class);
    }

    public function subscribes()
    {
        return $this->hasMany(Subscribe::class);
    }

    public function tags()
    {
       return $this->belongsToMany(Tag::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
