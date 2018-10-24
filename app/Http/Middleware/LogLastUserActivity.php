<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LogLastUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check()) {
            $expiresAt = Carbon::now()->addMinutes(3);
            cache()->put('user-is-online-' . Auth::id(), true, $expiresAt);
            User::all()->where('id', "=", Auth::id())->first()->push(['visited_at'=>NOW()]);
        }
        return $next($request);
    }
}
