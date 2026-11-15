<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// Módulo de usuarios - Anabel.
class EnsureUserRole
{
    public function handle(Request $request, Closure $next, string $rol): Response
    {
        abort_unless($request->user()?->rol === $rol, 403, 'No tienes acceso a este portal.');

        return $next($request);
    }
}
