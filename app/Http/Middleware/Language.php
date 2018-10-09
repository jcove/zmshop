<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($lang = request()->lang) {
            session(['lang' => $lang]);
        }
        if (Session::has('lang')) {
            App::setLocale(Session::get('lang'));
        }
        return $next($request);

    }
}