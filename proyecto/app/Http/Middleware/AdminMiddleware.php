<?php

/**
 * Middleware AdminMiddleware
 * 
 * Verifica que el usuario autenticado sea de tipo administrador.
 * 
 * @author Tu Nombre
 * @version 1.0
 * @since 2026-01-24
 * @category Middleware
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica si hay usuario autenticado y si es admin
        if (Auth::check() && Auth::user()->tipo === 'admin') {
            return $next($request);
        }

        // Si no es admin, redirige o muestra error 403
        abort(403, 'No tienes permiso para acceder a esta sección.');
    }
}