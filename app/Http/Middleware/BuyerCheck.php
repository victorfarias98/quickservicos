<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class BuyerCheck
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
        if (Auth::check() && 0 !== Auth::user()->user_type){
            return redirect()->to('/');
        }
        return $next($request);

    }
}
