<?php namespace Twedoo\StoneGuard\Middleware;

/**
 * This file is part of StoneGuard,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Twedoo\stoneGuard
 */

use Closure;
use Illuminate\Contracts\Auth\Guard;

class StoneGuardPermission
{
	const DELIMITER = '|';

	protected $auth;

	/**
	 * Creates a new instance of the middleware.
	 *
	 * @param Guard $auth
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  Closure $next
	 * @param  $permissions
	 * @return mixed
	 */
	public function handle($request, Closure $next, $permissions)
	{
		if (!is_array($permissions)) {
			$permissions = explode(self::DELIMITER, $permissions);
		}
		if ($this->auth->guest() || !$request->user()->can($permissions)) {
			abort(403);
		}

		return $next($request);
	}
}
