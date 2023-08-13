<?php namespace Twedoo\VolcatorGuard\Middleware;

/**
 * This file is part of Volcator,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Twedoo\volcatorGuard
 */

use Closure;
use Auth;

class UserEnable
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  Closure $next
	 * @param  $roles
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        if(auth()->check() && (auth()->user()->status == false)){
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'Your Account is suspended, please contact Admin.');
        }
        return $next($request);
	}
}
