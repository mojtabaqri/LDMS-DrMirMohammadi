<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class verifyMobileMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth('api')->user()->mobile_verified_at)
            return $next($request);
        return abort('403', 'Phone Not Verifed!');


    }
}
