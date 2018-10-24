<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable=[
        "name",
        "items",
        "phone",
        "address",
        "status",
        "market_slug",
        "user_id",
        "status",
        "total_price"
    ];
}
