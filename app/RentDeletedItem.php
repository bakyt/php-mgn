<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RentDeletedItem extends Model
{
    protected $fillable=[
        'category',
        'author',
        'phone_number',
        'messengers',
        'additional_info',
        'features',
        'price',
        'address'
    ];
}
