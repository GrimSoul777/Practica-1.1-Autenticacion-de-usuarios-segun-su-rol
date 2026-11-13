<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerificarRol
{
    public function handle(Request $request, Closure $next, string $rol): Response
    {
        // Comprobar si el usuario tiene el rol solicitado
        if (Auth::user()->rol->nombre !== $rol) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        return $next($request);
    }
}