<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    public static function findBySlug($slug){
        return static::all()->where('slug', $slug)->first();
    }
}
