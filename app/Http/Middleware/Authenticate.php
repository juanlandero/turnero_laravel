<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Contracts\Auth\Guard;
use App\Http\Controllers\Dashboard\LoginController;
use App\UserPrivilege;
use Closure;
use Session;
use Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if(!$request->expectsJson()) {
            return route('login');
        }
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check() && Session::has('_DASHBOARD_SESSION_') && Session::get('_DASHBOARD_SESSION_') == LoginController::DASHBOARD_SESSION) {
            if(!Session::has('privilegesMenu')) {
                Session::put("privilegesMenu", UserPrivilege::getPrivilegesMenu(Auth::user()));
            }
            
            View()->share('_PRIVILEGES_MENU_', Session::get('privilegesMenu'));

            return $next($request);
        } else {
            return redirect()->to('/dashboard/logout');
        }
    }
}
