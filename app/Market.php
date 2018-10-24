<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    protected $fillable=[
        'name',
        'description',
        'slug',
        'icon',
        'background',
        'slider',
        'administrator',
        'moderators',
        'type',
        'categories',
        'address',
        'about',
        'contacts',
        'is_sale',
        'delivery',
    ];
}
