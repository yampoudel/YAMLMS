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
        //check user is logged in and user is an admin
        if (auth()->check() && auth()->user()->isAdmin()) {
            return $next($request);
        }

        //if they are not admin stop them here
        abort(403, 'Access Denied : Admins only');
    }
}
