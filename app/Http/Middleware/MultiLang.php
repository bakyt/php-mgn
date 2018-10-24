<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\App;

class MultiLang
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
        if($request->get("lang")) {
            foreach(config("app.locales") as $value)
                if(request()->get("lang") == $value)
                    $request->session()->put("language", $value);
        }
        if(!$request->session()->get("language")) app()->setLocale(config('app.locale'));
        else app()->setLocale($request->session()->get("language"));
        Carbon::setLocale(app()->getLocale());
        return $next($request);
    }
}
