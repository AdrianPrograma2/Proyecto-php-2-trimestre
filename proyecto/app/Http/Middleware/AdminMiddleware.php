<?php



namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * 
     *
     * @param  \Closure(\Illuminate\Http\Request):
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