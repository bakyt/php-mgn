<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $fillable =[
        'phone_number',
        'name',
        'config',
        'visited_at'
    ];
    public function isOnline()
    {
        $visit = Carbon::parse($this->visited_at);
        return Carbon::now()->diff($visit)->i<1?trans('auth.online'):$visit->diffForHumans();
    }
}
