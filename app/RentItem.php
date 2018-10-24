<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RentItem extends Model
{
    protected $fillable=[
        'images',
        'category',
        'priority',
        'status',
        'author',
        'phone_number',
        'additional_info',
        'features',
        'price',
        'address',
        'views',
        'updated',
        'type',
        'state',
        'market',
        'content'
    ];
    protected $dates = ['created_at', 'updated_at', 'updated'];
    public static function findByCategoryId($id){
        return RentItem::all()->where('category', $id);
    }
}
