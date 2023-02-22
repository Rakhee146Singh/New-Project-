<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $modules, $permissions): Response
    {
        if (auth()->user()->type == 'superadmin') {
            return $next($request);
        } else {
            $user = auth()->user()->hasAccess($modules, $permissions);
            if ($user) {
                return $next($request);
            }
            return response()->json(['not access']);
        }
    }
}
