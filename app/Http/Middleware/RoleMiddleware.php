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
		if (!auth()->check()) {
			abort(403, 'Вы должны быть авторизованы для доступа к этому ресурсу.');
		}

		$userRole = auth()->user()->role;

		if ($userRole === 'admin') {
			return $next($request);
		}

		if ($role === 'moderator' && $userRole === 'moderator') {
			return $next($request);
		}
			
		abort(403, 'Недостаточно прав для доступа к этому ресурсу.');
    }
}
