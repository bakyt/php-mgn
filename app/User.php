<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;

class User extends \TCG\Voyager\Models\User
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'password',
        'username',
        'avatar',
        'gender',
        'birth_date',
        'phone_number',
        'phone_code',
        'visited_at',
        'delivery',
        'firebase_token',
        'market'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function isOnline()
    {
        $visit = Carbon::parse($this->visited_at);
        return Carbon::now()->diff($visit)->i<1?trans('auth.online'):$visit->diffForHumans();
    }
}
