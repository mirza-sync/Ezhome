<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // if (Auth::guard($guard)->check()) {
        //     return redirect(RouteServiceProvider::HOME);
        // }
       
        // switch added for multiuser redirect if auth
        switch($guard) {
            case 'landlord':
                if(Auth::guard($guard)->check()){
                    return redirect()->route('landlord.dashboard');
                }
                break;
            case 'admin':
                if(Auth::guard($guard)->check()){
                    return redirect()->route('admin.dashboard');
                }
                break;
            default:
                if(Auth::guard($guard)->check()){
                    // return redirect('/home');
                    return redirect()->intended();
                }
                break;
        }

        return $next($request);
    }
}
