<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
		$user = auth()->user();

		if (!$user) {
			abort(403, 'Вы должны быть авторизованы для доступа к этому ресурсу.');
		}

		if ($user->isAdmin()) {
			return $next($request);
		}

		foreach ($roles as $role) {
			if ($user->hasRole($role)) {
				return $next($request);
			}
		}
			
		abort(403, 'Недостаточно прав для доступа к этому ресурсу.');
    }
}
