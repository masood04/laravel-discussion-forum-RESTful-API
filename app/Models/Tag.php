<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function threads()
    {
        return  $this->belongsToMany(Thread::class,'tag_threads');
    }


    public function getRouteKeyName()
    {
        return 'slug';
    }
}
