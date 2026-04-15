<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RolMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $usuario = Auth::user();

        if (!$usuario || !$usuario->activo) {
            return redirect('/login');
        }

        if (!in_array($usuario->rol, $roles)) {
            abort(403, 'No tienes permiso para acceder');
        }

        return $next($request);
    }
}