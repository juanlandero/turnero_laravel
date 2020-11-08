<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Cookie;
use Closure;


class CheckPublicAccess
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
        if (session()->has('OFFICE')) {
            return $next($request);
        }
        return redirect()->route('public.index');
    }
}
