<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if (!auth()->check() || auth()->user()->role->name !== $role) {
            abort(403, 'Acceso denegado');
        }

        return $next($request);
    }
}

