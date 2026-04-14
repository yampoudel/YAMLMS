<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //check user is logged in and user is an admin or teacher
        //May teacher will go to another middleware later
        if (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->role === 'Teacher')) {
            return $next($request);
        }

        abort(403, 'Access Denied : Staff Only');
    }
}
